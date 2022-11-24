<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('id_types')->insert([
            ['id_type' => 'телефон'],
            ['id_type' => 'email']
        ]);
    }
}
