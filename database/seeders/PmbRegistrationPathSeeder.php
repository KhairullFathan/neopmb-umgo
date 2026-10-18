<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PmbRegistrationPath;

class PmbRegistrationPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code'=>'REG', 'name'=> 'REGULER','description'=>'Jalur pendaftaran reguler program studi non kesehatan dan non kedokteran'],
            ['code'=>'REGKES', 'name'=> 'REGULER KESEHATAN','description'=>'Jalur pendaftaran program studi kesehatan'],
            ['code'=>'REGKEDOK', 'name'=> 'REGULER KEDOKTERAN','description'=>'Jalur pendaftaran reguler program studi kedokteran'],
        ];
        foreach($data as $row){
            PmbRegistrationPath::create($row);
        }
    }
}
