<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Scan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['tanggal', 'nama_pd', 'menit_masuk', 'menit_pulang'];
    public function scan_masuk()
    {
        return $this->hasOne(Scan_masuk::class);
    }
    public function scan_pulang()
    {
        return $this->hasOne(Scan_pulang::class);
    }
    public function pd()
    {
        return $this->hasOne(Peserta_didik::class, 'peserta_didik_id', 'peserta_didik_id');
    }
    public function getTanggalAttribute(){
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('l, j F Y');
    }
    public function getMenitMasukAttribute(){
        return ($this->scan_masuk) ? Carbon::parse($this->scan_masuk['created_at'])->format('H:i') : NULL;
    }
    public function getMenitPulangAttribute(){
        return ($this->scan_pulang) ? Carbon::parse($this->scan_pulang['created_at'])->format('H:i') : NULL;
    }
    public function getNamaPdAttribute(){
        return ($this->pd) ? $this->pd->nama : NULL;
    }
}
