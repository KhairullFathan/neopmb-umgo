<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code'=> 'PAI','faculty_code'=>'FAI','name'=>'PENDIDIKAN AGAMA ISLAM','degree_level'=>'S1'],
            ['code'=> 'PIAUD','faculty_code'=>'FAI','name'=>'PENDIDIKAN ISLAM ANAK USIA DINI','degree_level'=>'S1'],
            ['code'=> 'SA','faculty_code'=>'FAI','name'=>'SASTRA ARAB','degree_level'=>'S1'],

            ['code'=> 'ANES','faculty_code'=>'FIKES','name'=>'KEPERAWATAN ANESTESI','degree_level'=>'DIV'],
            ['code'=> 'INFOMED','faculty_code'=>'FIKES','name'=>'INFORMATIKA MEDIS','degree_level'=>'S1'],
            ['code'=> 'KEBID','faculty_code'=>'FIKES','name'=>'KEBIDANAN','degree_level'=>'S1'],
            ['code'=> 'KEP','faculty_code'=>'FIKES','name'=>'KEPERAWATAN','degree_level'=>'S1'],
            ['code'=> 'TLM','faculty_code'=>'FIKES','name'=>'TEKNOLOGI LABORATORIUM MEDIS','degree_level'=>'DIV'],
            ['code'=> 'NS','faculty_code'=>'FIKES','name'=>'NERS','degree_level'=>'PROFESI'],
            ['code'=> 'PB','faculty_code'=>'FIKES','name'=>'PENDIDIKAN PROFESI BIDAN','degree_level'=>'PROFESI'],

            ['code'=> 'AGB','faculty_code'=>'FSIK','name'=>'AGRIBISNIS','degree_level'=>'S1'],
            ['code'=> 'BDP','faculty_code'=>'FSIK','name'=>'AKUAKULTUR','degree_level'=>'S1'],
            ['code'=> 'BISDIG','faculty_code'=>'FSIK','name'=>'BISNIS DIGITAL','degree_level'=>'S1'],
            ['code'=> 'GEO','faculty_code'=>'FSIK','name'=>'GEOGRAFI','degree_level'=>'S1'],
            ['code'=> 'ILKOM','faculty_code'=>'FSIK','name'=>'ILMU KOMPUTER','degree_level'=>'S1'],
            ['code'=> 'PTN','faculty_code'=>'FSIK','name'=>'PETERNAKAN','degree_level'=>'S1'],
            ['code'=> 'SI','faculty_code'=>'FSIK','name'=>'SISTEM INFORMASI','degree_level'=>'S1'],
            ['code'=> 'SIPIL','faculty_code'=>'FSIK','name'=>'TEKNIK SIPIL','degree_level'=>'S1'],

            ['code'=> 'KEDOK','faculty_code'=>'FK','name'=>'KEDOKTERAN','degree_level'=>'S1'],
            ['code'=> 'PRKEDOK','faculty_code'=>'FK','name'=>'PROFESI DOKTER','degree_level'=>'PROFESI'],

            ['code'=> 'ILKOR','faculty_code'=>'FKIP','name'=>'ILMU KEOLAHRAGAAN','degree_level'=>'S1'],
            ['code'=> 'IPA','faculty_code'=>'FKIP','name'=>'PENDIDIKAN ILMU PENGETAHUAN ALAM','degree_level'=>'S1'],
            ['code'=> 'MTK','faculty_code'=>'FKIP','name'=>'PENDIDIKAN MATEMATIKA','degree_level'=>'S1'],
            ['code'=> 'PGSD','faculty_code'=>'FKIP','name'=>'PENDIDIKAN GURU SEKOLAH DASAR','degree_level'=>'S1'],
            ['code'=> 'PPG','faculty_code'=>'FKIP','name'=>'PENDIDIKAN PROFESI GURU','degree_level'=>'PROFESI'],

            ['code'=> 'MDIKDAS','faculty_code'=>'PASCA','name'=>'MAGISTER PENDIDIKAN DASAR','degree_level'=>'S2'],
            ['code'=> 'MSDH','faculty_code'=>'PASCA','name'=>'MAGISTER MANAJEMEN SUMBER DAYA HAYATI','degree_level'=>'S2'],
            ['code'=> 'S2AP','faculty_code'=>'PASCA','name'=>'MAGISTER ADMINISTRASI PUBLIK','degree_level'=>'S2'],

            ['code'=> 'AK','faculty_code'=>'FIS','name'=>'AKUNTANSI','degree_level'=>'S1'],
            ['code'=> 'ANE','faculty_code'=>'FIS','name'=>'ADMINISTRASI PUBLIK','degree_level'=>'S1'],
            ['code'=> 'EP','faculty_code'=>'FIS','name'=>'EKONOMI PEMBANGUNAN','degree_level'=>'S1'],
            ['code'=> 'IH','faculty_code'=>'FIS','name'=>'ILMU HUKUM','degree_level'=>'S1'],
            ['code'=> 'PAR','faculty_code'=>'FIS','name'=>'PARIWISATA','degree_level'=>'S1'],
            ['code'=> 'SIG','faculty_code'=>'FIS','name'=>'SASTRA INGGRIS','degree_level'=>'S1'],

            ['code'=> 'PSI','faculty_code'=>'FPSI','name'=>'PSIKOLOGI','degree_level'=>'S1'],
        ];
        foreach($data as $row){
            StudyProgram::create($row);
        }
    }
}
