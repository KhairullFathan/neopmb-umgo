<?php

namespace App\Console\Commands;

use App\Models\Province;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('wilayah:sync-provinces')]
#[Description('Sinkronisasi data provinsi dari API Wilayah Indonesia (wilayah.id)')]
class SyncProvinces extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengambil data provinsi dari API...');
        try {
            $response = Http::timeout(3000)
                ->retry(3, 1000)
                ->get('https://wilayah.id/api/provinces.json');
            if($response->failed()){
                $this->error('Gagal mengambil data dari API. HTTP status: '. $response->status());
                return self::FAILURE;
            }
            $provinces = $response->json()['data'];
            if(!is_array($provinces)){
                $this->error('Gagal mengambil data dari API. Response API tidak valid.');
                return self::FAILURE;
            }
            $data = [];
            foreach($provinces as $row){
                if(empty($row['code']) || empty($row['name'])) continue;
                $data[] = [
                    'code'=> $row['code'],
                    'name'=> $row['name'],
                ];
            }
            if(empty($data)){
                $this->error('Tidak ada data provinsi yang ditemukan.');
                return self::FAILURE;
            }
            foreach (array_chunk($data, 1000) as $chunk) {
                Province::upsert($chunk, ['code'], ['name']);
            }
            $num = Province::count();
            $this->newLine();
            $this->info('Sinkronisasi berhasil: '.$num. ' provinsi');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan: '.$e->getMessage());
            return self::FAILURE;
        }
    }
}
