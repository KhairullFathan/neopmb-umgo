<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PmbRegistrationStage;

class PmbRegistrationStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code'=>'PENDAFTARAN', 'name'=> 'Pendaftaran'],
            ['code'=>'PEMBAYARAN_FORMULIR', 'name'=> 'Pembayaran Formulir'],
            ['code'=>'LENGKAPI_DATA_BERKAS', 'name'=> 'Lengkapi Data & Berkas'],
            ['code'=>'SELEKSI_BERKAS', 'name'=> 'Seleksi Berkas'],
            ['code'=>'UJIAN_ONLINE', 'name'=> 'Ujian Online'],
            ['code'=>'TES_OFFLINE', 'name'=> 'Tes Offline'],
            ['code'=>'WAWANCARA', 'name'=> 'Wawancara'],
            ['code'=>'PSIKOTES', 'name'=> 'Psikotes'],
            ['code'=>'PENGUMUMAN_SELEKSI_BERKAS', 'name'=> 'Pengumuman Seleksi Berkas'],
            ['code'=>'PENGUMUMAN_KELULUSAN', 'name'=> 'Pengumuman Kelulusan'],
            ['code'=>'REGISTRASI_ULANG', 'name'=> 'Registrasi Ulang'],
        ];
        foreach($data as $row){
            PmbRegistrationStage::create($row);
        }
    }
}
