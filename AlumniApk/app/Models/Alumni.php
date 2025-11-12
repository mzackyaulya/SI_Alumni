<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'nis', 'nisn', 'nama', 'email', 'phone',
        'jenis_kelamin', 'nama_ortu', 'sttp',
        'angkatan', 'jurusan', 'pekerjaan', 'perusahaan',
        'alamat', 'tempat_lahir', 'tanggal_lahir',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date', // atau 'immutable_date'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lamarans()
    {
        return $this->hasMany(Lamaran::class);
    }

}
