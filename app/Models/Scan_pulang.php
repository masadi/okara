<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Scan_pulang extends Model
{
    use HasFactory;
    protected $table = 'scan_pulang';
	protected $guarded = [];
    protected $appends = ['tanggal_indo', 'menit'];
    public function getTanggalIndoAttribute(){
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('d F Y');
    }
    public function getMenitAttribute(){
        return Carbon::parse($this->attributes['created_at'])->format('H:i:s');
    }
}
