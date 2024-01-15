<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;
    protected $table = 'ref.kelompok';
	protected $primaryKey = 'kelompok_id';
	protected $guarded = [];
    public function bentuk_pendidikan()
    {
        return $this->hasOne(Bentuk_pendidikan::class, 'bentuk_pendidikan_id', 'bentuk_pendidikan_id');
    }
}
