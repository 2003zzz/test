<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptt05status extends Model
{
    use HasFactory;
    protected $table = 'ptt05.ptt05status';

    protected $primary_key = 'id_status';

    protected $attributes = [];
    
    protected $fillable = [];

    protected $hidden = [];
    
    protected $casts = [];
}
