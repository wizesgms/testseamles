<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlayerAccount;
use Illuminate\Support\Str;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $player = new PlayerAccount();
        $player->username = Str::random(5);
        $player->ext_id = Str::random(6);
        $player->playerid = Str::random(7);
        $player->agentcode = Str::random(8);
        $player->password = Str::random(9);
        $player->balance = 100000;
        $player->before_balance = 100000;
        $player->status = 1;
        $player->save();
    }
}
