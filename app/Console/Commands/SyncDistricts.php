<?php

namespace App\Console\Commands;

use App\Models\Regency;
use App\Models\District;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('wilayah:sync-districts')]
#[Description('Sinkronisasi data kecamatan dari API Wilayah Indonesia (wilayah.id)')]
class SyncDistricts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengambil data kecamatan dari API...');
        try {
            $regencies = Regency::all();
            $data = [];
            foreach($regencies as $rr){
                $response = Http::timeout(300)
                    ->retry(3, 1000)
                    ->get('https://wilayah.id/api/districts/'.$rr->code.'.json');
                if($response->failed()){
                    $this->error('Gagal mengambil data dari API. HTTP status: '. $response->status());
                    return self::FAILURE;
                }
                $districts = $response->json()['data'];
                if(!is_array($districts)){
                    $this->error('Gagal mengambil data dari API. Response API tidak valid.');
                    return self::FAILURE;
                }
                foreach($districts as $row){
                    if(empty($row['code']) || empty($row['name'])) continue;
                    $data[] = [
                        'code'=> $row['code'],
                        'code_regency'=> $rr->code,
                        'name'=> $row['name'],
                    ];
                }
                if(empty($data)){
                    $this->error('Tidak ada data kecamatan yang ditemukan.');
                    return self::FAILURE;
                }
                foreach (array_chunk($data, 1000) as $chunk) {
                    District::upsert($chunk, ['code'], ['code_regency','name']);
                }
            }
            $num = District::count();
            $this->newLine();
            $this->info('Sinkronisasi berhasil: '.$num. ' kecamatan');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
