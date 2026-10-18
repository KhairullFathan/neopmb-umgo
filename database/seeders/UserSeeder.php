<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id'=> 1,
                'username'=> 'khairulfathan',
                'email'=> 'fathan@umgo.ac.id',
                'phone'=> '081241589493',
                'password'=> Hash::make('12345678'),
                'is_active'=> true,
            ],
        ];
        foreach($data as $row){
            User::create($row);
        }
        User::find(1)->assignRole("superadmin");
    }
}
