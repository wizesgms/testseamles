<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ApisSeamles;
use App\Models\SeamlesTransaction;
use App\Models\PlayerAccount;

class SeamlessController extends Controller
{
    public function seamles($method, Request $request)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data['Transactions']) {
            $this->insertTrans($data);
        }

        switch ($method) {
            case 'GetBalance':
                return $this->GetBalance($data);
                break;
            case 'PlaceBet':
                return $this->PlaceBet($data);
                break;
        }
    }

    private function GetBalance($data)
    {
        $agentApi = ApisSeamles::where('agentcode', $data['OperatorCode'])->first();
        $player = PlayerAccount::where('playerid', $data['MemberName'])->first();
        $getsign = $this->generateSign($agentApi->agentcode, $data['RequestTime'], 'getbalance', $agentApi->secretkey);

        if ($data['sign'] != $getsign) {
            return response()->json([
                'ErrorCode' => 1004,
                'ErrorMessage' => 'API Invalid Sign',
                'Balance' => 0,
                'BeforeBalance' => 0
            ]);
        } elseif (!$player) {
            return response()->json([
                'ErrorCode' => 1000,
                'ErrorMessage' => 'API Member Not Exists',
                'Balance' => 0,
                'BeforeBalance' => 0
            ]);
        } elseif (!empty($player)) {
            return response()->json([
                'ErrorCode' => 0,
                'ErrorMessage' => 'Success',
                'Balance' => $player->balance,
                'BeforeBalance' => 0
            ]);
        }
    }

    private function PlaceBet($data)
    {
        $agentApi = ApisSeamles::where('agentcode', $data['OperatorCode'])->first();
        $player = PlayerAccount::where('playerid', $data['MemberName'])->first();
        $transaction = SeamlesTransaction::where('TransactionID', $data['Transactions']['TransactionID'])->first();
        $getsign = $this->generateSign($agentApi->agentcode, $data['RequestTime'], 'placeBet', $agentApi->secretkey);

        if ($data['sign'] != $getsign) {
            return response()->json([
                'ErrorCode' => 1004,
                'ErrorMessage' => 'API Invalid Sign',
                'Balance' => 0,
                'BeforeBalance' => 0
            ]);
        } elseif (!$player) {
            return response()->json([
                'ErrorCode' => 1000,
                'ErrorMessage' => 'API Member Not Exists',
                'Balance' => 0,
                'BeforeBalance' => 0
            ]);
        } elseif (!empty($transaction)) {
            return response()->json([
                'ErrorCode' => 1003,
                'ErrorMessage' => 'API Duplicate Transaction',
                'Balance' => 0,
                'BeforeBalance' => 0
            ]);
        } elseif (!empty($player)) {

            if ($player->balance < $data['Transactions']['ValidBetAmount']) {
                return response()->json([
                    'ErrorCode' => 1001,
                    'ErrorMessage' => 'API Member Insufficient Balance',
                    'Balance' => $data['Transactions']['TransactionAmount'],
                    'BeforeBalance' => $player->before_balance
                ]);
            }
            
            $player->balance_before = $player->balance;
            $player->balance = $player->balance += $data['Transactions']['TransactionAmount'];
            $player->save();

            return response()->json([
                'ErrorCode' => 0,
                'ErrorMessage' => 'Success',
                'Balance' => $player->balance,
                'BeforeBalance' => $player->before_balance
            ]);
        }
    }



    function generateSign($OperatorCode, $RequestTime, $MethodName, $SecretKey)
    {
        $sign = md5($OperatorCode . $RequestTime . $MethodName . $SecretKey);
        return $sign;
    }

    function insertTrans($data)
    {
        $transaction = new SeamlesTransaction();
        $transaction->playerID = $data['MemberName'];
        $transaction->MemberID = $data['Transactions']['MemberID'];
        $transaction->OperatorID = $data['Transactions']['OperatorID'];
        $transaction->ProductID = $data['Transactions']['ProductID'];
        $transaction->ProviderID = $data['Transactions']['ProviderID'];
        $transaction->ProviderLineID = $data['Transactions']['ProviderLineID'];
        $transaction->WagerID = $data['Transactions']['WagerID'];
        $transaction->CurrencyID = $data['Transactions']['CurrencyID'];
        $transaction->GameType = $data['Transactions']['GameType'];
        $transaction->GameID = $data['Transactions']['GameID'];
        $transaction->GameRoundID = $data['Transactions']['GameRoundID'];
        $transaction->ValidBetAmount = $data['Transactions']['ValidBetAmount'];
        $transaction->BetAmount = $data['Transactions']['BetAmount'];
        $transaction->TransactionAmount = $data['Transactions']['TransactionAmount'];
        $transaction->TransactionID = $data['Transactions']['TransactionID'];
        $transaction->PayoutAmount = $data['Transactions']['PayoutAmount'];
        $transaction->PayoutDetail = $data['Transactions']['PayoutDetail'];
        $transaction->CommissionAmount = $data['Transactions']['CommissionAmount'];
        $transaction->JackpotAmount = $data['Transactions']['JackpotAmount'];
        $transaction->SettlementDate = $data['Transactions']['SettlementDate'];
        $transaction->JPBet = $data['Transactions']['JPBet'];
        $transaction->Status = $data['Transactions']['Status'];
        $transaction->save();
    }
}
