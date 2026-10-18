<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code'=> 'FSIK',
                'name'=> 'FAKULTAS SAINS DAN ILMU KOMPUTER',
            ],
            [
                'code'=> 'FIS',
                'name'=> 'FAKULTAS ILMU SOSIAL',
            ],
            [
                'code'=> 'FIKES',
                'name'=> 'FAKULTAS ILMU KESEHATAN',
            ],
            [
                'code'=> 'FK',
                'name'=> 'FAKULTAS KEDOKTERAN',
            ],
            [
                'code'=> 'FAI',
                'name'=> 'FAKULTAS AGAMA ISLAM',
            ],
            [
                'code'=> 'FPSI',
                'name'=> 'FAKULTAS PSIKOLOGI',
            ],
            [
                'code'=> 'PASCA',
                'name'=> 'PASCASARJANA',
            ],
            [
                'code'=> 'FKIP',
                'name'=> 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN',
            ],
        ];
        foreach($data as $row){
            Faculty::create($row);
        }
    }
}
