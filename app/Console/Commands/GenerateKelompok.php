<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kelompok;

class GenerateKelompok extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:kelompok';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Kelompok::whereNull('bentuk_pendidikan_id')->update(['bentuk_pendidikan_id' => 15]);
        //->where('kurikulum', '<>', 0)
        $kelompok = [
            5 => [
                [
                    'nama' => 'Kelompok A (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok B (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok C (Muatan Lokal)',
                    'kurikulum' => 2013,
                ],
            ], //sd
            6 => [
                [
                    'nama' => 'Kelompok A (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok B (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok C (Muatan Lokal)',
                    'kurikulum' => 2013,
                ],
            ], //smp
            13 => [
                [
                    'nama' => 'Kelompok A (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok B (Umum)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok C (Peminatan)',
                    'kurikulum' => 2013,
                ],
                [
                    'nama' => 'Kelompok D (Muatan Lokal)',
                    'kurikulum' => 2013,
                ],
            ], //sma
        ];
        foreach($kelompok as $bentuk_pendidikan_id => $kel){
            foreach($kel as $data){
                Kelompok::updateOrCreate(
                    [
                        'bentuk_pendidikan_id' => $bentuk_pendidikan_id,
                        'nama_kelompok' => $data['nama'],
                        'kurikulum' => $data['kurikulum']
                    ],
                    [
                        'last_sync' => now(),
                    ]
                );
            }
        }
        return Command::SUCCESS;
    }
}
