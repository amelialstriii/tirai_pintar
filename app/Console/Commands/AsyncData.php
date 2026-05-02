<?php

namespace App\Console\Commands;

use App\Models\Alat;
use App\Models\Sensor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync and fetch data from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this -> info ("Memulai sinkronisasi data...");

        $url = config ('services.tirai.api');

        if(!$url) {
            $this->error ("URL API tidak ditemukan. Pastikan konfigurasi sudah benar.");
            return Command::FAILURE;
        }

        // try and catch

        try {
            $response = Http::timeout(1)-> acceptJson()->get($url);
            if (!$response->successful()) {
                $this->error("Gagal mendapatkan data dari API. Statyus code: " .
                $response->status());
                return Command::FAILURE;
            }

            $data = $response->json();

            if (!isset($data['project']) || 
                !isset($data['timestamp']) ||
                !isset($data['data']['LDR']) || 
                !isset($data['data']['RELAY_1'])) {
                    $this->error("Struktur data tidak sesuai dengan yang di API");
                    return Command::FAILURE;
            }

            $project = $data['project'];
            $recordedAt = $data['timestamp'];
            $ldr = (string) $data['data']['LDR'];
            $relay1 = (bool) $data['data']['RELAY_1'];

            $LastReading = Sensor::where('project', $project)->latest('record_at')->first();

            if(
                $LastReading && $LastReading->ldr === $ldr && (bool) $LastReading->relay_1 === $relay1
            
            ) {
                $this->info("Tidak ada perubahan data. Tidak perlu menyimpan data baru.");
                return Command::SUCCESS;
            }

            $alat = Alat::firstOrCreate([
                'project' => $project,
                'device_code' => 'TIRAI-001'
            ]);

            Sensor:: create([
                'alat_id' => $alat->id,
                'project' => $project,
                'record_at' => $recordedAt,
                'ldr' => $ldr,
                'relay_1' => $relay1,
                'raw_data' => $data,
            ]);

            $this->info("data berhasil disimpan");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            Log::error('sync TIRAI Error', [
                'message' => $e->getMessage(),
            ]);

            $this->error('Terjadi error: ' . $e->getMessage());

            return Command::FAILURE;
        }

    }
}
