<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;


class SyncUserApiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(): void
    {
        Cache::put('sync_user_status', 'processing');
        Cache::put('sync_user_progress', 0);
        Cache::put('sync_user_total', 0);
        Cache::put('sync_user_current', 0);

        try {

            $response = Http::withHeaders([
                'X-API-KEY' => config('services.feeder.key'),
                'Accept' => 'application/json'
            ])->get(config('services.feeder.url'));

            if (!$response->successful()) {
                Log::error('API gagal diakses');
                Cache::put('sync_user_status', 'failed');
                return;
            }

            $result = $response->json();

            if (!isset($result['data'])) {
                Log::error('Format data API salah');
                Cache::put('sync_user_status', 'failed');
                return;
            }

            $data = $result['data'];
            $total = count($data);

            // 🔥 simpan total
            Cache::put('sync_user_total', $total);

            // ❗ hindari division by zero
            if ($total == 0) {
                Cache::put('sync_user_progress', 100);
                Cache::put('sync_user_status', 'done');
                return;
            }

            $current = 0;

            collect($data)
                ->chunk(100)
                ->each(function ($chunk) use (&$current, $total) {

                    foreach ($chunk as $item) {

                        User::updateOrCreate(
                            ['username' => $item['username'] ?? null],
                            [
                                'email' => $item['email'] ?? null,
                                'password' => Hash::make($item['username'] ?? '123456'),
                                'role' => 'mahasiswa',

                                'first_name' => $item['first_name'] ?? null,
                                'last_name'  => $item['last_name'] ?? null,
                                'phone' => $item['phone'] ?? null,

                                'id_mahasiswa' => $item['id_mahasiswa'] ?? null,
                                'id_registrasi_mahasiswa' => $item['id_registrasi_mahasiswa'] ?? null,
                                'id_prodi' => $item['id_prodi'] ?? null,
                                'status_mahasiswa' => $item['status_mahasiswa'] ?? null,
                                'id_periode' => $item['id_periode'] ?? null,

                                'data_mahasiswa' => isset($item['data_mahasiswa']) 
                                    ? json_encode($item['data_mahasiswa']) 
                                    : null,

                                'profile_pic' => $item['profile_pic'] ?? null,
                            ]
                        );

                        $current++;

                        // 🔥 simpan current
                        Cache::put('sync_user_current', $current);

                        $progress = intval(($current / $total) * 100);
                        Cache::put('sync_user_progress', $progress);
                    }
                });

            Log::info('Sync user selesai. Total: ' . $total);

            Cache::put('sync_user_progress', 100);
            Cache::put('sync_user_status', 'done');

        } catch (\Exception $e) {

            Log::error('Error sync: ' . $e->getMessage());

            Cache::put('sync_user_status', 'failed');
        }
    }
}