<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pak01e01 extends Model
{
    use HasFactory;
    protected $table = 'pak01.pak01e01';

    protected $primaryKey = 'p501';

    public $timestamps = false;

    protected $keyType = 'string';

    protected $attributes = [];

    protected $fillable = [];

    protected $hidden = [
        'p452'
    ];

    protected $casts = [];
}
