<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doa extends Model
{
    protected $table = 'doa';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi
    protected $fillable = [
        'sumber',
        'judul',
        'arab',
        'indo',
        'createdAt'
    ];

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = null;
}
