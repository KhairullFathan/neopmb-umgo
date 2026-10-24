<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                ['key'=>'dashboard'],
                [
                    'id'=> 1,
                    'parent_id'=> null,
                    'name'=>'Dashboard',
                    'route'=>'dashboard',
                    'active_route' => 'dashboard.*',
                    'icon'=> 'dashboard',
                    'permission'=>'dashboard.view',
                    'sort_order'=>1,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'masterdata'],
                [
                    'id'=> 2,
                    'parent_id'=> null,
                    'name'=>'Masterdata',
                    'route'=> null,
                    'active_route' => 'masterdata.*',
                    'icon'=>'masterdata',
                    'permission'=>null,
                    'sort_order'=>2,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'masterdata.faculty'],
                [
                    'id'=> 3,
                    'parent_id'=> 2,
                    'name'=>'Fakultas',
                    'route'=> 'masterdata.faculty.index',
                    'active_route'=> 'masterdata.faculty.*',
                    'icon'=> null,
                    'permission'=>'masterdata.faculty.view',
                    'sort_order'=>1,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'masterdata.study_program'],
                [
                    'id'=> 4,
                    'parent_id'=> 2,
                    'name'=>'Program Studi',
                    'route'=> 'masterdata.study_program.index',
                    'active_route'=> 'masterdata.study_program.*',
                    'icon'=> null,
                    'permission'=>'masterdata.study_program.view',
                    'sort_order'=>2,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'user'],
                [
                    'id'=> 5,
                    'parent_id'=> null,
                    'name'=>'Pengguna',
                    'route'=> 'user.index',
                    'active_route'=> 'user.*',
                    'icon'=> 'users',
                    'permission'=>'user.view',
                    'sort_order'=>3,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'registrant'],
                [
                    'id'=> 6,
                    'parent_id'=> null,
                    'name'=>'Akun Pendaftar',
                    'route'=> 'registrant.index',
                    'active_route'=> 'registrant.*',
                    'icon'=> 'users',
                    'permission'=>'registrant.view',
                    'sort_order'=>4,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'pmb.registration_path'],
                [
                    'id'=> 7,
                    'parent_id'=> null,
                    'name'=>'Jalur Pendaftaran',
                    'route'=> 'pmb.registration_path.index',
                    'active_route'=> 'pmb.registration_path.*',
                    'icon'=> 'path',
                    'permission'=>'pmb.registration_path.view',
                    'sort_order'=>5,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'pmb.period'],
                [
                    'id'=> 8,
                    'parent_id'=> null,
                    'name'=>'Periode PMB',
                    'route'=> 'pmb.period.index',
                    'active_route'=> 'pmb.period.*',
                    'icon'=> 'calendar',
                    'permission'=>'pmb.period.view',
                    'sort_order'=>6,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'pmb.wave'],
                [
                    'id'=> 9,
                    'parent_id'=> null,
                    'name'=>'Gelombang PMB',
                    'route'=> 'pmb.wave.index',
                    'active_route'=> 'pmb.wave.*',
                    'icon'=> 'calendar',
                    'permission'=>'pmb.wave.view',
                    'sort_order'=>7,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'pmb.application'],
                [
                    'id'=> 10,
                    'parent_id'=> null,
                    'name'=>'Formulir PMB',
                    'route'=> 'pmb.application.index',
                    'active_route'=> 'pmb.application.*',
                    'icon'=> 'register',
                    'permission'=>'pmb.application.view',
                    'sort_order'=>8,
                    'is_active'=>true,
                ]
            ],
            [
                ['key'=>'pmb.graduation'],
                [
                    'id'=> 11,
                    'parent_id'=> null,
                    'name'=>'Kelulusan PMB',
                    'route'=> 'pmb.graduation.index',
                    'active_route'=> 'pmb.graduation.*',
                    'icon'=> 'graduate',
                    'permission'=>'pmb.graduation.view',
                    'sort_order'=>9,
                    'is_active'=>true,
                ]
            ],
        ];
        foreach($data as $row){
            // var_dump()
            Menu::updateOrCreate($row[0],$row[1]);
        }

    }
}
