<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Tenat'
            ],
            [
                'name' => 'Workers'
            ],
            [
                'name' => 'Technician'
            ],
            [
                'name' => 'Visitor'
            ]
        ]);
    }
}
