<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ayat extends Model
{
    use HasFactory;

    protected $table = 'ayat';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'surah_id',
        'nomor',
        'ar',
        'tr',
        'idn',
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

    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }
}
