<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barcode;
use App\Models\Scan;
use App\Models\Scan_masuk;
use App\Models\Scan_pulang;
use App\Models\Peserta_didik;
use File;

class PostController extends Controller
{
    public function index(Request $request, User $user)
    {
        return [
            'data' => Scan::withWhereHas('pd')->with(['scan_masuk', 'scan_pulang'])->where('user_id', $request->user()->user_id)->whereHas('scan_masuk')->where('tanggal', now()->format('Y-m-d'))->orderBy('created_at', 'DESC')->get()
        ];
    }
    public function scan(Request $request){
        $user = auth()->user();
        $peserta_didik_id = $request->barcode;
        $find = Peserta_didik::find($peserta_didik_id);
        if($find){
            $scan = Scan::updateOrCreate([
                'user_id' => $user->user_id,
                'peserta_didik_id' => $peserta_didik_id,
                'tanggal' => now()->format('Y-m-d'),
            ]);
            if(request()->jenis == 'masuk'){
                Scan_masuk::updateOrCreate([
                    'scan_id' => $scan->id,
                ]);
            } else {
                Scan_pulang::updateOrCreate([
                    'scan_id' => $scan->id,
                ]);
            }
            $success = TRUE;
            $text = (request()->jenis == 'masuk') ? $find->nama.' Berhasil Scan Masuk' : $find->nama.' Berhasil Scan Pulang';
        } else {
            $success = FALSE;
            $text = (request()->jenis == 'masuk') ? 'Scan Masuk Gagal' : 'Scan Pulang Gagal';
        }
        $data = [
            'success' => $success,
            'request' => $request->all(),
            'user' => $user,
            'text' => $text,
            'qrcode' => $peserta_didik_id,
            'find' => $find,
            //url('storage/qrcodes/'.$id.'.svg'),
        ];
        return $data;
    }
    public function scanOld(Request $request){
        $user = auth()->user();
        $barcode = Barcode::where('nama', $request->barcode)->whereStatus(1)->first();
        $success = FALSE;
        $text = '';
        $folder = storage_path('app/public/qrcodes');
        $id = Str::random(10);
        if (!File::isDirectory($folder)) {
            //MAKA FOLDER TERSEBUT AKAN DIBUAT
            File::makeDirectory($folder, 0777, true, true);
        }
        if($barcode){
            $scan = Scan::updateOrCreate([
                'user_id' => $user->user_id,
                'tanggal' => now()->format('Y-m-d'),
            ]);
            if(request()->jenis == 'masuk'){
                Scan_masuk::updateOrCreate([
                    'scan_id' => $scan->id,
                ]);
            } else {
                Scan_pulang::updateOrCreate([
                    'scan_id' => $scan->id,
                ]);
            }
            $barcode->status = 0;
            $barcode->save();
            $success = TRUE;
            $text = (request()->jenis == 'masuk') ? 'Scan Masuk Berhasil' : 'Scan Pulang Berhasil';
        } else {
            $text = (request()->jenis == 'masuk') ? 'Scan Masuk Gagal' : 'Scan Pulang Gagal';
        }
        Barcode::truncate();
        QrCode::backgroundColor(255,255,255)->size(500)->generate($id, storage_path('app/public/qrcodes/'.$id.'.svg'));
        Barcode::create([
            'nama' => $id,
            'status' => 1,
        ]);
        $data = [
            'success' => $success,
            'request' => $request->all(),
            'user' => $user,
            'text' => $text,
            'qrcode' => $id,
            //url('storage/qrcodes/'.$id.'.svg'),
        ];
        return $data;
    }
    public function get_qrcode(){
        $barcode = Barcode::whereStatus(1)->first();
        return $barcode;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
