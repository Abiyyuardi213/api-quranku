<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hadist extends Model
{
    protected $table = 'hadist';

    protected $fillable = [
        'jenisId',
        'no',
        'judul',
        'arab',
        'indo',
        'createdAt'
    ];

    public $timestamps = false;

    public function jenis()
    {
        return $this->belongsTo(JenisHadist::class, 'jenisId', 'id');
    }
}
