<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Surah extends Model
{
    use HasFactory;

    protected $table = 'surah';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nomor',
        'nama_arab',
        'nama_latin',
        'jumlah_ayat',
        'tempat_turun',
        'arti',
        'deskripsi',
        'audio',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function ayat()
    {
        return $this->hasMany(Ayat::class, 'surah_id')->orderBy('nomor');
    }
}
