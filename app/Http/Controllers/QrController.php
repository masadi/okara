<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\Peserta_didik;

class QrController extends Controller
{
    public function index(){
        $pd = Peserta_didik::find(request()->route('peserta_didik_id'));
        $data = [
            'pd' => $pd,
            'qrcode' => base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($pd->peserta_didik_id??'string')),
        ];
        return view('qr', $data);
    }
}
