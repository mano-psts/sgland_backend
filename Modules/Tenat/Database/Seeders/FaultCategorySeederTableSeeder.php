<?php

namespace Modules\Tenat\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Tenat\Entities\FaultCategory;
use Illuminate\Support\Facades\DB;

class FaultCategorySeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('fault_categories')->insert([
            [
                'name' => 'Equipmental Faults'
            ],
            [
                'name' => 'Structural Faults'
            ],
            [
                'name' => 'Other Faults'
            ],
            [
                'name' => 'Toilet Faults'
            ]
        ]);
    }
}
