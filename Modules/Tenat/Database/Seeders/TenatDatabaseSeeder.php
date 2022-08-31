<?php

namespace Modules\Tenat\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TenatDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(FaultCategorySeederTableSeeder::class);

        // $this->call("OthersTableSeeder");
    }
}
