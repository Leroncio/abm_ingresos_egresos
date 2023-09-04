<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        TransactionType::updateOrCreate([
            'id'=>1,
            'type'=>'Ingresos'
        ]);

        TransactionType::updateOrCreate([
            'id'=>2,
            'type'=>'Gastos'
        ]);


    }
}
