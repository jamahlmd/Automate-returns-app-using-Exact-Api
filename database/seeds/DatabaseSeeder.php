<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        for ($x = 0; $x <= 80; $x++) {
            DB::table('retours')->insert([
                'employee' => rand(10,2000),
                'customer_name' => str_random(10)
            ]);
        }


    }
}
