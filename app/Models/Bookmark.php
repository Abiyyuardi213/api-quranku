<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $fillable = [
        'surahNumber',
        'surahName',
        'ayahNumber',
        'arabicText',
        'translation',
        'timestamp',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->timestamp)) {
                $model->timestamp = round(microtime(true) * 1000);
            }
        });
    }
}
