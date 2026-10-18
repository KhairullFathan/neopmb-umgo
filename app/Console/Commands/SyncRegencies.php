<?php

namespace App\Console\Commands;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;


#[Signature('wilayah:sync-regencies')]
#[Description('Sinkronisasi data kabupaten/kota dari API Wilayah Indonesia (wilayah.id)')]
class SyncRegencies extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengambil data kabupaten/kota dari API...');
        try {
            $provinces = Province::all();
            $data = [];
            foreach($provinces as $rp){
                $response = Http::timeout(300)
                    ->retry(3, 1000)
                    ->get('https://wilayah.id/api/regencies/'.$rp->code.'.json');
                if($response->failed()){
                    $this->error('Gagal mengambil data dari API. HTTP status: '. $response->status());
                    return self::FAILURE;
                }
                $regencies = $response->json()['data'];
                if(!is_array($regencies)){
                    $this->error('Gagal mengambil data dari API. Response API tidak valid.');
                    return self::FAILURE;
                }
                foreach($regencies as $row){
                    if(empty($row['code']) || empty($row['name'])) continue;
                    $data[] = [
                        'code'=> $row['code'],
                        'code_province'=> $rp->code,
                        'name'=> $row['name'],
                    ];
                }
                if(empty($data)){
                    $this->error('Tidak ada data kabupaten/kota yang ditemukan.');
                    return self::FAILURE;
                }
                foreach (array_chunk($data, 1000) as $chunk) {
                    Regency::upsert($chunk, ['code'], ['code_province','name']);
                }
            }
            $num = Regency::count();
            $this->newLine();
            $this->info('Sinkronisasi berhasil: '.$num. ' kabupaten/kota');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
