<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Village;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('wilayah:sync-villages')]
#[Description('Sinkronisasi data desa/kelurahan dari API Wilayah Indonesia (wilayah.id)')]
class SyncVillages extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengambil data desa/kelurahan dari API...');
        try {
            $districts = District::all();
            $data = [];
            foreach($districts as $rd){
                $response = Http::timeout(300)
                    ->retry(3, 1000)
                    ->get('https://wilayah.id/api/villages/'.$rd->code.'.json');
                if($response->failed()){
                    $this->error('Gagal mengambil data dari API. HTTP status: '. $response->status());
                    return self::FAILURE;
                }
                $villages = $response->json()['data'];
                if(!is_array($villages)){
                    $this->error('Gagal mengambil data dari API. Response API tidak valid.');
                    return self::FAILURE;
                }
                foreach($villages as $row){
                    if(empty($row['code']) || empty($row['name'])) continue;
                    $data[] = [
                        'code'=> $row['code'],
                        'code_district'=> $rd->code,
                        'name'=> $row['name'],
                    ];
                }
                if(empty($data)){
                    $this->error('Tidak ada data desa/kelurahan yang ditemukan.');
                    return self::FAILURE;
                }
                foreach (array_chunk($data, 1000) as $chunk) {
                    Village::upsert($chunk, ['code'], ['code_district','name']);
                }
            }
            $num = Village::count();
            $this->newLine();
            $this->info('Sinkronisasi berhasil: '.$num. ' desa/kelurahan');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
