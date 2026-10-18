<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name'=> 'Bank Syariah Indonesia (BSI)','account_name'=>'Universitas Muhammadiyah Gorontalo','account_number'=>'7200806117','is_active'=>true],
            ['name'=> 'Muamalat','account_name'=>'Universitas Muhammadiyah Gorontalo','account_number'=>'8120001146','is_active'=>true],
        ];
        foreach($data as $row){
            BankAccount::create($row);
        }
    }
}
