<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Anggota_rombel;
use App\Models\Pembelajaran;
use App\Models\Kelompok;
use App\Models\Guru;

class RombelController extends Controller
{
    public function index(){
        $data = Rombongan_belajar::where(function($query){
            $query->where('jenis_rombel', request()->jenis_rombel);
            $query->where('semester_id', request()->semester_id);
            if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                $query->where('sekolah_id', request()->sekolah_id);
            }
        })->with([
            'wali_kelas' => function($query){
                $query->select('guru_id', 'nama');
            },
            'jurusan_sp' => function($query){
                $query->select('jurusan_sp_id', 'nama_jurusan_sp');
            },
            'kurikulum' => function($query){
                $query->select('kurikulum_id', 'nama_kurikulum');
            },
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->orderBy('nama', request()->sortbydesc)
        ->when(request()->q, function($query){
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->orWhereHas('wali_kelas', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('jurusan_sp', function($query){
                $query->where('nama_jurusan_sp', 'ILIKE', '%' . request()->q . '%');
            });
            $query->orWhereHas('kurikulum', function($query){
                $query->where('nama_kurikulum', 'ILIKE', '%' . request()->q . '%');
            });
        })->paginate(request()->per_page);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function anggota_rombel(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $data = Peserta_didik::with(['agama'])->withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        return response()->json(['data' => $data, 'rombel' => $rombel]);
    }
    public function keluarkan(){
        $data = Anggota_rombel::find(request()->anggota_rombel_id);
        if($data->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Anggota Rombel berhasil dikeluarkan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Anggota Rombel gagal dikeluarkan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function pembelajaran(){
        $rombel = Rombongan_belajar::find(request()->rombongan_belajar_id);
        $merdeka = Str::of($rombel->kurikulum->nama_kurikulum)->contains('Merdeka');
        if($merdeka){
            $kurikulum = 2022;
        } else {
            $kurikulum = 2017;
        }
        $data = Pembelajaran::with(['guru', 'pengajar'])->where(function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            $query->whereNull('induk_pembelajaran_id');
        })->orderBy('mata_pelajaran_id')->get();
        return response()->json([
            'data' => $data,
            'guru' => Guru::where(function($query){
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
                $query->whereDoesntHave('ptk_keluar', function($query){
                    $query->where('semester_id', request()->semester_id);
                });
                $query->select('guru_id', 'nama');
            })->orderBy('nama')->get(),
            'Kelompok' => Kelompok::where(function($query) use ($kurikulum){
                $query->where('kurikulum', $kurikulum);
                if($kurikulum != 2022){
                    $query->orWhere('kurikulum', 0);
                }
            })->orderBy('kelompok_id')->get(),
            'rombel' => $rombel,
        ]);
    }
    public function simpan_pembelajaran(){
        $pembelajaran = Pembelajaran::find(request()->pembelajaran_id);
        $pembelajaran->nama_mata_pelajaran = request()->nama_mata_pelajaran;
        $pembelajaran->guru_pengajar_id = request()->guru_pengajar_id;
        $pembelajaran->kelompok_id = request()->kelompok_id;
        $pembelajaran->no_urut = request()->no_urut;
        if($pembelajaran->save()){
            $data = [
                'icon' => 'CoffeeIcon',
                'variant' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pembelajaran '.request()->nama_mata_pelajaran.' berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'XOctagonIcon',
                'variant' => 'error',
                'title' => 'Gagal',
                'text' => 'Pembelajaran gagal '.request()->nama_mata_pelajaran.' disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function hapus_pembelajaran(){
        $pembelajaran = Pembelajaran::find(request()->pembelajaran_id);
        $nama = $pembelajaran->nama_mata_pelajaran;
        if($pembelajaran->delete()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pembelajaran '.$nama.' berhasil dihapus',
                'pembelajaran' => Pembelajaran::with(['guru', 'pengajar'])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('mata_pelajaran_id')->get(),
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Pembelajaran '.$nama.' berhasil dihapus',
                'pembelajaran' => Pembelajaran::with(['guru', 'pengajar'])->where('rombongan_belajar_id', request()->rombongan_belajar_id)->orderBy('mata_pelajaran_id')->get(),
            ];
        }
        return response()->json($data);
    }
    public function get_rombel(){
        $data = Rombongan_belajar::where(function($query){
            $query->where('tingkat', request()->tingkat);
            $query->where('jenis_rombel', 1);
            $query->where('semester_id', request()->semester_id);
            if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                $query->where('sekolah_id', request()->sekolah_id);
            }
            if(request()->aksi){
                if(request()->aksi == 'rencana'){
                    $query->whereHas('pembelajaran', function($query){
                        $query->whereHas('induk', function($query){
                            $query->where('mata_pelajaran_id', '200040000');
                            $query->where('guru_id', request()->guru_id);
                        });
                    });
                }
                if(request()->aksi == 'nilai'){
                    $query->whereHas('pembelajaran', function($query){
                        $query->whereHas('induk', function($query){
                            $query->where('mata_pelajaran_id', '200040000');
                            $query->where('guru_id', request()->guru_id);
                        });
                        $query->has('rencana_projek');
                    });
                }
                if(request()->aksi == 'rencana-ukk'){
                    $query->whereHas('jurusan_sp', function($query){
                        $query->has('paket_ukk');
                    });
                }
            }
        })->orderBy('nama')->get();
        return response()->json(['data' => $data]);
    }
    public function get_pembelajaran(){
        $data_kelompok = Kelompok::get();
        $kelompok = [];
        foreach ($data_kelompok as $kel){
            $bentuk_pendidikan = ($kel->bentuk_pendidikan) ? '('.$kel->bentuk_pendidikan->nama.'-'.$kel->kurikulum.')' : '';
            $kelompok[] = [
                'kelompok_id' => $kel->kelompok_id,
                'nama_kelompok' => $kel->nama_kelompok.' '.$bentuk_pendidikan,
            ];
        }
        $data = [
            'pembelajaran' => Pembelajaran::where(function($query){
                $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
                $query->where('semester_id', request()->semester_id);
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
            })->get(),
            'rombel' => Rombongan_belajar::where(function($query){
                $query->where('jenis_rombel', 1);
                $query->where('semester_id', request()->semester_id);
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
            })->orderBy('tingkat')->get(),
            'guru' => Guru::where(function($query){
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
                //$query->whereDoesntHave('ptk_keluar', function($query){
                //    $query->where('semester_id', request()->semester_id);
                //});
                $query->select('guru_id', 'nama');
            })->orderBy('nama')->get(),
            'kelompok' => $kelompok,
        ];
        return response()->json($data);
    }
    public function insert_pembelajaran(){
        $insert = 0;
        foreach(request()->rombongan_belajar_id as $urut => $rombongan_belajar_id){
            $find = Pembelajaran::where(function($query) use ($rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $rombongan_belajar_id);
                $query->where('mata_pelajaran_id', request()->mata_pelajaran_id);
            })->first();
            if($find){
                $find->guru_id = request()->guru_id[$urut];
                $find->kelompok_id = request()->kelompok_id[$urut];
                $find->no_urut = request()->no_urut[$urut];
                if($find->save()){
                    $insert++;
                }
            } else {
                $insert++;
                $pembelajaran_id = Str::uuid();
                $rombel = Rombongan_belajar::find($rombongan_belajar_id);
                Pembelajaran::create([
                    'pembelajaran_id' => $pembelajaran_id,
                    'sekolah_id' => $rombel->sekolah_id,
                    'semester_id' => request()->semester_id,
                    'nama_mata_pelajaran' => request()->nama,
                    'rombongan_belajar_id' => $rombongan_belajar_id,
                    'mata_pelajaran_id' => request()->mata_pelajaran_id,
                    'guru_id' => request()->guru_id[$urut],
                    'kelompok_id' => request()->kelompok_id[$urut],
                    'no_urut' => request()->no_urut[$urut],
                    'last_sync' => now(),
                ]);
            }
        }
        if($insert){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Pembelajaran berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Pembelajaran gagal disimpan',
            ];
        }
        return response()->json($data);
    }
}
