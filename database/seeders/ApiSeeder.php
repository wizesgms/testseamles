<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApisSeamles;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apis = new ApisSeamles();
        $apis->apikey = 'E634';
        $apis->secretkey = 'uC28Xy';
        $apis->agentcode = 'E634';
        $apis->apiurl = 'https://swmd.6633663.com/';
        $apis->addons_url = 'https://swmd.6633663.com/testcase/index';
        $apis->save();
    }
}
