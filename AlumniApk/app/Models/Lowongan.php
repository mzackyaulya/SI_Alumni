<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Lowongan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id','perusahaan_id','judul','slug','tipe','level','lokasi',
        'gaji_min','gaji_max','deskripsi','kualifikasi','tag','deadline','aktif'
    ];

    protected $casts = [
        'tag'      => 'array',
        'deadline' => 'date',
        'aktif'    => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->id))   $m->id   = (string) Str::uuid();
            if (empty($m->slug)) $m->slug = static::makeUniqueSlug($m->judul);
        });

        static::updating(function ($m) {
            if ($m->isDirty('judul')) {
                $m->slug = static::makeUniqueSlug($m->judul, $m->id);
            }
        });
    }

    protected static function makeUniqueSlug(string $judul, ?string $ignoreId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
                ->when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
                ->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    // optional: route model binding by slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // scope untuk filter di index()
    public function scopeFilter($q, array $f)
    {
        $q->when($f['q']   ?? null, fn($w,$v)=>$w->where(function($s) use($v){
            $s->where('judul','like',"%$v%")
              ->orWhere('lokasi','like',"%$v%")
              ->orWhereJsonContains('tag', $v);
        }));
        $q->when($f['tipe']  ?? null, fn($w,$v)=>$w->where('tipe',$v));
        $q->when($f['level'] ?? null, fn($w,$v)=>$w->where('level',$v));
        $q->when($f['lokasi']?? null, fn($w,$v)=>$w->where('lokasi','like',"%$v%"));
    }

    public function perusahaan(){
        return $this->belongsTo(Perusahaan::class);
    }
    
    public function lamarans(){
        return $this->hasMany(Lamaran::class);
    }
}
