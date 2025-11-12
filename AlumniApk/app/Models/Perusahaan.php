<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Perusahaan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id','user_id','nama','industri','website','email','telepon',
        'alamat','kota','logo','npwp','siup','dokumen_legal','is_verified','verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id)) $m->id = (string) Str::uuid();
        });
    }

    /* ================= Relasi ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lowongans()
    {
        return $this->hasMany(Lowongan::class);
    }

    /* ================= Scope Filter ================= */

    public function scopeFilter($q, array $f)
    {
        return $q
            ->when($f['q'] ?? null, fn($x,$s)=>$x->where(fn($w)=>$w
                ->where('nama','like',"%$s%")
                ->orWhere('industri','like',"%$s%")
                ->orWhere('kota','like',"%$s%")))
            ->when($f['industri'] ?? null, fn($x,$v)=>$x->where('industri',$v))
            ->when($f['verified'] ?? null, fn($x,$v)=>$x->where('is_verified',(bool)$v));
    }

    /* ================= Accessor (helper URL) ================= */

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::disk('public')->url($this->logo);
        }
        return asset('assets/img/company.png'); // fallback ikon perusahaan
    }

    public function getLegalUrlAttribute(): ?string
    {
        if ($this->dokumen_legal && Storage::disk('public')->exists($this->dokumen_legal)) {
            return Storage::disk('public')->url($this->dokumen_legal);
        }
        return null;
    }
}
