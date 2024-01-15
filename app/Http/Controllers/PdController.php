<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Anggota_rombel;
use App\Models\Jurusan_sp;
use App\Models\Pekerjaan;
use App\Models\Agama;
use App\Models\Mst_wilayah;

class PdController extends Controller
{
    private function kondisi(){
        return function($query){
            if(request()->status == 'aktif' || request()->status == 'password'){
                $query->whereHas('anggota_rombel', function($query){
                    if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                    $query->where('semester_id', request()->semester_id);
                    $query->whereHas('rombongan_belajar', function($query){
                        if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                            $query->where('sekolah_id', request()->sekolah_id);
                        }
                        $query->where('semester_id', request()->semester_id);
                        $query->where('jenis_rombel', 1);
                        if(auth()->user()->hasRole('wali', request()->periode_aktif)){
                            $query->where('guru_id', auth()->user()->guru_id);
                        }
                    });
                });
            } else {
                $query->whereHas('pd_keluar', function($query){
                    if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                    $query->where('semester_id', request()->semester_id);
                });
                /*$query->whereHas('anggota_rombel', function($query){
                    if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                    $query->where('semester_id', request()->semester_id);
                    $query->whereHas('rombongan_belajar', function($query){
                        if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                            $query->where('sekolah_id', request()->sekolah_id);
                        }
                        $query->where('semester_id', request()->semester_id);
                        $query->where('jenis_rombel', 1);
                        $query->where('guru_id', request()->guru_id);
                    });
                });*/
            }
        };
    }
    public function index(){
        $data = Peserta_didik::where($this->kondisi())
        ->with([
            'agama', 
            'anggota_rombel' => function($query){
                $query->withWhereHas('rombongan_belajar', function($query){
                    if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                        $query->where('sekolah_id', request()->sekolah_id);
                    }
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jenis_rombel', 1);
                });
            },
            'user' => function($query){
                $query->select('user_id', 'peserta_didik_id', 'password', 'default_password', 'last_login_at');
            }
        ])
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->when(request()->q, function($query) {
            $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhere('nisn', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhere('tempat_lahir', 'ILIKE', '%' . request()->q . '%');
            $query->where($this->kondisi());
            $query->orWhereHas('agama', function($query){
                $query->where('nama', 'ILIKE', '%' . request()->q . '%');
            });
            $query->where($this->kondisi());
        })->when(request()->tingkat, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->wherehas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('tingkat', request()->tingkat);
                });
            });
            $query->where($this->kondisi());
        })->when(request()->jurusan_sp_id, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->wherehas('rombongan_belajar', function($query){
                    $query->where('semester_id', request()->semester_id);
                    $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                });
            });
            $query->where($this->kondisi());
        })->when(request()->rombongan_belajar_id, function($query){
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
            });
            $query->where($this->kondisi());
        })->paginate(request()->per_page);
        $jurusan_sp = [];
        $rombel = [];
        if(request()->tingkat){
            $jurusan_sp = Jurusan_sp::select('jurusan_sp_id', 'nama_jurusan_sp')->whereHas('rombongan_belajar', function($query){
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
                $query->where('semester_id', request()->semester_id);
            })->orderBy('nama_jurusan_sp')->get();
        }
        if(request()->jurusan_sp_id){
            $rombel = Rombongan_belajar::select('rombongan_belajar_id', 'nama', 'jurusan_sp_id')->where(function($query){
                $query->where('tingkat', request()->tingkat);
                $query->where('jurusan_sp_id', request()->jurusan_sp_id);
                if(!loggedUser()->hasRole('superadmin', request()->periode_aktif)){
                    $query->where('sekolah_id', request()->sekolah_id);
                }
                $query->where('semester_id', request()->semester_id);
            })->orderBy('tingkat')->orderBy('nama')->get();
        }
        return response()->json(['status' => 'success', 'data' => $data, 'jurusan_sp' => $jurusan_sp, 'rombel' => $rombel]);
    }
    public function detil(){
        $data = Peserta_didik::with(['agama', 'pekerjaan_ayah', 'pekerjaan_ibu', 'pekerjaan_wali', 'wilayah'])->find(request()->peserta_didik_id);
        return response()->json([
            'data' => $data, 
            'pekerjaan' => Pekerjaan::orderBy('pekerjaan_id')->get(),
            'data_agama' => Agama::orderBy('agama_id')->get(),
            'provinsi' => Mst_wilayah::where('id_level_wilayah', 1)->orderBy('kode_wilayah')->get(),
            'kabupaten' => Mst_wilayah::where('id_level_wilayah', 2)->where('mst_kode_wilayah', $data->wilayah->parrentRecursive->parrentRecursive->parrentRecursive->kode_wilayah)->orderBy('kode_wilayah')->get(),
            'kecamatan' => Mst_wilayah::where('id_level_wilayah', 3)->where('mst_kode_wilayah', $data->wilayah->parrentRecursive->parrentRecursive->kode_wilayah)->orderBy('kode_wilayah')->get(),
            'desa' => Mst_wilayah::where('id_level_wilayah', 4)->where('mst_kode_wilayah', $data->wilayah->parrentRecursive->kode_wilayah)->orderBy('kode_wilayah')->get(),
        ]);
    }
    public function update(){
        request()->validate(
            [
                'peserta_didik_id' => 'required',
                'nama' => 'required',
                'nis' => 'required',
                'nisn' => 'required',
                'nik' => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama_id' => 'required',
                'status' => 'required',
                'anak_ke' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'provinsi' => 'required',
                'kabupaten' => 'required',
                'desa_kelurahan' => 'required',
                'kecamatan' => 'required',
                'kode_pos' => 'required',
                'no_telp' => 'required',
                'sekolah_asal' => 'required',
                'diterima_kelas' => 'required',
                'diterima' => 'required',
                'email' => 'required',
                'nama_ayah' => 'required',
                'kerja_ayah' => 'required',
                'nama_ibu' => 'required',
                'kerja_ibu' => 'required',
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong!',
                'nis.required' => 'NIS tidak boleh kosong!',
                'nisn.required' => 'NISN tidak boleh kosong!',
                'nik.required' => 'NIK tidak boleh kosong!',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong!',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong!',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong!',
                'agama_id.required' => 'Agama tidak boleh kosong!',
                'status.required' => 'Status tidak boleh kosong!',
                'anak_ke.required' => 'Anak Ke tidak boleh kosong!',
                'alamat.required' => 'Alamat tidak boleh kosong!',
                'rt.required' => 'RT tidak boleh kosong!',
                'rw.required' => 'RW tidak boleh kosong!',
                'provinsi.required' => 'Provinsi tidak boleh kosong!',
                'kabupaten.required' => 'Kabupaten/Kota tidak boleh kosong!',
                'kecamatan.required' => 'Kecamatan tidak boleh kosong!',
                'desa_kelurahan.required' => 'Desa/Kelurahan tidak boleh kosong!',
                'kode_pos.required' => 'Kodepos tidak boleh kosong!',
                'no_telp.required' => 'Nomor Telpon tidak boleh kosong!',
                'sekolah_asal.required' => 'Sekolah Asal tidak boleh kosong!',
                'diterima_kelas.required' => 'Diterima Kelas tidak boleh kosong!',
                'diterima.required' => 'Diterima tidak boleh kosong!',
                'email.required' => 'Email tidak boleh kosong!',
                'nama_ayah.required' => 'Nama Ayah tidak boleh kosong!',
                'kerja_ayah.required' => 'Pekerjaan Ayah tidak boleh kosong!',
                'nama_ibu.required' => 'Nama Ibu tidak boleh kosong!',
                'kerja_ibu.required' => 'Pekerjaan tidak boleh kosong!',
            ]
        );
        $pd = Peserta_didik::find(request()->peserta_didik_id);
        /*$pd->status = request()->status;
        $pd->anak_ke = request()->anak_ke;
        $pd->diterima_kelas = request()->diterima_kelas;
        $pd->email = request()->email;
        $pd->nama_wali = request()->nama_wali;
        $pd->alamat_wali = request()->alamat_wali;
        $pd->telp_wali = request()->telp_wali;
        $pd->kerja_wali = request()->kerja_wali;*/
        $pd->nama = request()->nama;
        $pd->no_induk = request()->nis;
        $pd->nisn = request()->nisn;
        $pd->nik = request()->nik;
        $pd->jenis_kelamin = request()->jenis_kelamin;
        $pd->tempat_lahir = request()->tempat_lahir;
        $pd->tanggal_lahir = request()->tanggal_lahir;
        $pd->agama_id = request()->agama_id;
        $pd->status = request()->status;
        $pd->anak_ke = request()->anak_ke;
        $pd->alamat = request()->alamat;
        $pd->rt = request()->rt;
        $pd->rw = request()->rw;
        $pd->kode_wilayah = request()->desa_kelurahan;
        //$pd->kabupaten = request()->kabupaten;
        //$pd->kecamatan = request()->kecamatan;
        //$pd->desa_kelurahan = request()->desa_kelurahan;
        $pd->kode_pos = request()->kode_pos;
        $pd->no_telp = request()->no_telp;
        $pd->sekolah_asal = request()->sekolah_asal;
        $pd->diterima_kelas = request()->diterima_kelas;
        $pd->diterima = request()->diterima;
        $pd->email = request()->email;
        $pd->nama_ayah = request()->nama_ayah;
        $pd->kerja_ayah = request()->kerja_ayah;
        $pd->nama_ibu = request()->nama_ibu;
        $pd->kerja_ibu = request()->kerja_ibu;
        $pd->nama_wali = request()->nama_wali;
        $pd->alamat_wali = request()->alamat_wali;
        $pd->telp_wali = request()->telp_wali;
        $pd->kerja_wali = request()->kerja_wali;
        if($pd->save()){
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data '.$pd->nama.' berhasil diperbaharui',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data '.$pd->nama.' Gagal diperbaharui. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
    public function get_pd(){
        $data = Peserta_didik::withWhereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', request()->rombongan_belajar_id);
        })->orderBy('nama')->get();
        return response()->json(['data_siswa' => $data]);
    }
    public function referensi(){
        $data = [
            'pekerjaan' => Pekerjaan::orderBy('pekerjaan_id')->get(),
            'data_agama' => Agama::orderBy('agama_id')->get(),
            'provinsi' => Mst_wilayah::where('id_level_wilayah', 1)->orderBy('kode_wilayah')->get(),
        ];
        return response()->json($data);
    }
    public function kabupaten(){
        $data = Mst_wilayah::where('id_level_wilayah', 2)->where(DB::raw("TRIM(mst_kode_wilayah)"), request()->provinsi)->orderBy('kode_wilayah')->get();
        return response()->json($data);
    }
    public function kecamatan(){
        $data = Mst_wilayah::where('id_level_wilayah', 3)->where(DB::raw("TRIM(mst_kode_wilayah)"), request()->kabupaten)->orderBy('kode_wilayah')->get();
        return response()->json($data);
    }
    public function desa(){
        $data = Mst_wilayah::where('id_level_wilayah', 4)->where(DB::raw("TRIM(mst_kode_wilayah)"), request()->kecamatan)->orderBy('kode_wilayah')->get();
        return response()->json($data);
    }
    public function simpan(){
        if(!loggedUser()->hasRole('wali', request()->periode_aktif)){
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda tidak memiliki akses untuk menambah Data Peserta Didik',
            ];
            return response()->json($data);
        }
        request()->validate(
            [
                'nama' => 'required',
                'nis' => 'required',
                'nisn' => 'required',
                'nik' => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama_id' => 'required',
                'status' => 'required',
                'anak_ke' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'provinsi' => 'required',
                'kabupaten' => 'required',
                'desa_kelurahan' => 'required',
                'kecamatan' => 'required',
                'kode_pos' => 'required',
                'no_telp' => 'required',
                'sekolah_asal' => 'required',
                'diterima_kelas' => 'required',
                'diterima' => 'required',
                'email' => 'required',
                'nama_ayah' => 'required',
                'kerja_ayah' => 'required',
                'nama_ibu' => 'required',
                'kerja_ibu' => 'required',
            ],
            [
                'nama.required' => 'Nama tidak boleh kosong!',
                'nis.required' => 'NIS tidak boleh kosong!',
                'nisn.required' => 'NISN tidak boleh kosong!',
                'nik.required' => 'NIK tidak boleh kosong!',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong!',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong!',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong!',
                'agama_id.required' => 'Agama tidak boleh kosong!',
                'status.required' => 'Status tidak boleh kosong!',
                'anak_ke.required' => 'Anak Ke tidak boleh kosong!',
                'alamat.required' => 'Alamat tidak boleh kosong!',
                'rt.required' => 'RT tidak boleh kosong!',
                'rw.required' => 'RW tidak boleh kosong!',
                'provinsi.required' => 'Provinsi tidak boleh kosong!',
                'kabupaten.required' => 'Kabupaten/Kota tidak boleh kosong!',
                'kecamatan.required' => 'Kecamatan tidak boleh kosong!',
                'desa_kelurahan.required' => 'Desa/Kelurahan tidak boleh kosong!',
                'kode_pos.required' => 'Kodepos tidak boleh kosong!',
                'no_telp.required' => 'Nomor Telpon tidak boleh kosong!',
                'sekolah_asal.required' => 'Sekolah Asal tidak boleh kosong!',
                'diterima_kelas.required' => 'Diterima Kelas tidak boleh kosong!',
                'diterima.required' => 'Diterima tidak boleh kosong!',
                'email.required' => 'Email tidak boleh kosong!',
                'nama_ayah.required' => 'Nama Ayah tidak boleh kosong!',
                'kerja_ayah.required' => 'Pekerjaan Ayah tidak boleh kosong!',
                'nama_ibu.required' => 'Nama Ibu tidak boleh kosong!',
                'kerja_ibu.required' => 'Pekerjaan tidak boleh kosong!',
            ]
        );
        $pd = Peserta_didik::create([
            'peserta_didik_id' => Str::uuid(),
            'sekolah_id' => request()->sekolah_id,
            'nama' => request()->nama,
            'no_induk' => request()->nis,
            'nisn' => request()->nisn,
            'nik' => request()->nik,
            'jenis_kelamin' => request()->jenis_kelamin,
            'tempat_lahir' => request()->tempat_lahir,
            'tanggal_lahir' => request()->tanggal_lahir,
            'agama_id' => request()->agama_id,
            'status' => request()->status,
            'anak_ke' => request()->anak_ke,
            'alamat' => request()->alamat,
            'rt' => request()->rt,
            'rw' => request()->rw,
            'kode_wilayah' => request()->desa_kelurahan,
            'kode_pos' => request()->kode_pos,
            'no_telp' => request()->no_telp,
            'sekolah_asal' => request()->sekolah_asal,
            'diterima_kelas' => request()->diterima_kelas,
            'diterima' => request()->diterima,
            'email' => request()->email,
            'nama_ayah' => request()->nama_ayah,
            'kerja_ayah' => request()->kerja_ayah,
            'nama_ibu' => request()->nama_ibu,
            'kerja_ibu' => request()->kerja_ibu,
            'nama_wali' => request()->nama_wali,
            'alamat_wali' => request()->alamat_wali,
            'telp_wali' => request()->telp_wali,
            'kerja_wali' => request()->kerja_wali,
            'last_sync' => now(),
        ]);
        if($pd){
            $rombel = Rombongan_belajar::where(function($query){
                $query->where('semester_id', request()->semester_id);
                $query->where('guru_id', request()->guru_id);
                $query->where('sekolah_id', request()->sekolah_id);
            })->first();
            if($rombel){
                $uuid = Str::uuid();
                Anggota_rombel::insert([
                    'peserta_didik_id' => $pd->peserta_didik_id,
                    'rombongan_belajar_id' => $rombel->rombongan_belajar_id,
                    'sekolah_id' => request()->sekolah_id,
                    'semester_id' => request()->semester_id,
                    'anggota_rombel_id' => $uuid,
                    'anggota_rombel_id_dapodik' => $uuid,
                    'last_sync' => now(),
                ]);
            }
            $data = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data Peserta Didik berhasil disimpan',
            ];
        } else {
            $data = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data Peserta Didik Gagal disimpan. Silahkan coba beberapa saat lagi!',
            ];
        }
        return response()->json($data);
    }
}
