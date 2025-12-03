<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pkt50_v extends Model
{
    use HasFactory;
    protected $table = 'ptt05.pkt50_v';

    protected $attributes = [];

    protected $fillable = [];

    protected $visible = [
        'snm_as_scaption',
        'kategoryprof',
        'profcode70'
    ];

    protected $casts = [];
}
