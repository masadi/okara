<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota_rombel;

class GenerateSatuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:satuan';

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
        $uuid = 'c7e4b50a-471e-42b9-b2d3-fb803d2c287c';
        $rombongan_belajar_id = '98a83f10-49fb-4b4e-ab3a-34e4eb77b0a6';
        $peserta_didik_id = 'd973a2da-cd74-42a2-a81b-7395f4ba1f44';
        $sekolah_id = '00c493eb-5a34-e211-aca7-e99f32efc3e1';
        $semester_id = '20231';
        Anggota_rombel::updateOrCreate(
            [
                'peserta_didik_id' => $peserta_didik_id,
                'rombongan_belajar_id' => $rombongan_belajar_id,
            ],
            [
                'sekolah_id' => $sekolah_id,
                'semester_id' => $semester_id,
                'anggota_rombel_id' => $uuid,
                'anggota_rombel_id_dapodik' => $uuid,
                'last_sync' => now(),
            ]
        );
        return Command::SUCCESS;
    }
}
