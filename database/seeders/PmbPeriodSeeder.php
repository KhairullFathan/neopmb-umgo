<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PmbPeriod;

class PmbPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id'=>'20252', 'name'=> '2025/2026 Genap','is_active'=>true],
            ['id'=>'20261', 'name'=> '2026/2027 Ganjil','is_active'=>true],
        ];
        foreach($data as $row){
            PmbPeriod::create($row);
        }
    }
}
