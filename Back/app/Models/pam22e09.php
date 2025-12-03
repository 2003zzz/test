<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pam22e09 extends Model
{
    use HasFactory;
    protected $table = 'pam00.pam22e09';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $attributes = [];
    protected $fillable = [
        'p003',
        'c006',
    ];
    protected $hidden = [
        'p0082'
    ];
    protected $casts = [];
}
