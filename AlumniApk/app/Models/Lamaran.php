<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Lamaran extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id','lowongan_id','alumni_id','status',
        'cv_path','surat_lamaran_path','portfolio_url','catatan','jadwal_interview'
    ];

    protected $casts = [
        'jadwal_interview' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id)) $m->id = (string) Str::uuid();
        });
    }

    public function lowongan(){
        return $this->belongsTo(Lowongan::class);
    }
    
    public function alumni(){
        return $this->belongsTo(Alumni::class);
    }
}
