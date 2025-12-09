<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHadist extends Model
{
    protected $table = 'jenishadist';

    protected $fillable = [
        'nama',
    ];

    public $timestamps = false;
}
