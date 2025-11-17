<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType   = 'string';
    protected $table = 'events';


    protected $fillable = [
        'id','title','slug','deskripsi','location',
        'start_at','end_at','is_published'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted() {
        static::creating(function (self $e) {
            $e->id   ??= (string) Str::uuid();
            $e->slug ??= Str::slug(Str::limit($e->title, 60, ''), '-') . '-' . Str::random(6);
        });
    }

    // untuk route model binding berbasis slug (public)
    public function getRouteKeyName() { return 'slug'; }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getTanggalAttribute()
    {
        return $this->start_at->format('d M Y') .
            ($this->end_at ? ' - ' . $this->end_at->format('d M Y') : '');
    }

}
