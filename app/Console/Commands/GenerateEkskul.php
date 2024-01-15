<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Ekstrakurikuler;
use App\Models\Anggota_rombel;
use App\Models\Peserta_didik;
use App\Models\Semester;
use App\Models\Sekolah;
use Carbon\Carbon;

class GenerateEkskul extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:ekskul';

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
        $semester = Semester::where('periode_aktif', 1)->first();
        $data = [
            1 => [
                'sekolah_id' => 'e0a7687c-8d18-e111-b811-099575ae97f6',
                'ekskul_id' => '0b6035b0-a1c4-4415-a973-50a6e46eca69',
                'rombel_id' => '95daaedf-56db-4e4f-9542-4a21e4d4e597',
                'nama' => 'Pramuka',
                'ptk_id' => '6d099480-e408-11e5-9ef9-b39459d13bb8',
            ],
            2 => [
                'sekolah_id' => 'e0a7687c-8d18-e111-b811-099575ae97f6',
                'ekskul_id' => '9f8731ad-dbd5-44c3-89bb-0a6e6acd9aaf',
                'rombel_id' => '60c7a7f4-e12a-44eb-b821-e4b5ed17ba4a',
                'nama' => 'Baca Tulis Al-Qur\'an',
                'ptk_id' => '6d099480-e408-11e5-9ef9-b39459d13bb8',
            ],
            3 => [
                'sekolah_id' => '297ef003-0282-4a62-8776-eed40edf9c9b',
                'ekskul_id' => 'afa65138-a273-4433-aa26-6ed86dd29c85',
                'rombel_id' => '18fd5d93-82a8-4398-8bf2-cfc36d7f517e',
                'nama' => 'Pramuka',
                'ptk_id' => '559b6aa1-1245-4d77-88b5-1bc64d16f23c',
            ],
        ];
        foreach($data as $d){
            $data_siswa = Peserta_didik::where(function($query) use ($semester, $d){
                $query->where('sekolah_id', $d['sekolah_id']);
                $query->whereHas('anggota_rombel', function($query) use ($semester, $d){
                    $query->where('sekolah_id', $d['sekolah_id']);
                    $query->where('semester_id', $semester->semester_id);
                    $query->whereHas('rombongan_belajar', function($query) use ($semester, $d){
                        $query->where('sekolah_id', $d['sekolah_id']);
                        $query->where('semester_id', $semester->semester_id);
                        $query->where('jenis_rombel', 1);
                    });
                });
            })->get();
            dump($data_siswa->count());
            $rombel = Rombongan_belajar::updateOrCreate(
                [
                    'rombongan_belajar_id' => $d['rombel_id'],
                ],
                [
                    'sekolah_id' => $d['sekolah_id'],
                    'semester_id' => $semester->semester_id,
                    'jurusan_id' => NULL,
                    'jurusan_sp_id' => NULL,
                    'kurikulum_id' => 99,
                    'nama' => $d['nama'],
                    'guru_id' => $d['ptk_id'],
                    'ptk_id' => $d['ptk_id'],
                    'tingkat' => 0,
                    'jenis_rombel' => 51,
                    'rombel_id_dapodik' => $d['rombel_id'],
                    'last_sync' => Carbon::now()->subDays(30),
                ]
            );
            $ekskul = Ekstrakurikuler::updateOrCreate(
                [
                    'ekstrakurikuler_id' => $d['ekskul_id'],
                ],
                [
                    'id_kelas_ekskul' => $d['ekskul_id'],
                    'semester_id' => $semester->semester_id,
                    'sekolah_id'	=> $d['sekolah_id'],
                    'guru_id' => $d['ptk_id'],
                    'nama_ekskul' => $d['nama'],
                    'is_dapodik' => 1,
                    'rombongan_belajar_id'	=> $d['rombel_id'],
                    'alamat_ekskul' => 'Lapangan', 
                    'last_sync'	=> Carbon::now()->subDays(30),
                ]
            );
            foreach($data_siswa as $siswa){
                $uuid = Str::uuid();
                Anggota_rombel::updateOrCreate(
                    [
                        'peserta_didik_id' => $siswa->peserta_didik_id,
                        'rombongan_belajar_id' => $d['rombel_id'],
                    ],
                    [
                        'sekolah_id' => $d['sekolah_id'],
                        'semester_id' => $semester->semester_id,
                        'anggota_rombel_id' => $uuid,
                        'anggota_rombel_id_dapodik' => $uuid,
                        'last_sync' => Carbon::now()->subDays(30),
                    ]
                );
            }
        }
        return Command::SUCCESS;
    }
}
