<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptt05v01 extends Model
{
    use HasFactory;
    protected $table = 'ptt05.ptt05v01';

    protected $primaryKey = 'id_v01';

    public $timestamps = false;

    protected $attributes = [];

    protected $fillable = [
        'code_detail',          // прежнее название -> p003, в модели pam22e09 название все ещё p003
        'workshop',
        'party',
        'service_number',
        'number_technological_notification',
        'cipher_main_td',
        'type_technical_doc',
        'note',
        'designation',
        'id_status',
        'id_version',
        'notification_number_ott',
        'create_service_number',
        'number_of_parts_in_batch',
        'validity_period_norms',
        'minimum_number_blanks',
        'id_e05',
        'laboriousness_on_dse',
        'total_laboriousness_electroperations',
        'laboriousness_on_dse_kzo',
        'total_laboriousness_workshop',
        'total_laboriousness_workshop_kzo',
        'laboriousness_controloperations_dse_workshop',
        'laboriousness_controloperations_dse_workshop_kzo',
        'laboriousness_controloper_dse_wshs',
        'laboriousness_controloper_dse_wshs_kzo',
        'laboriousness_on_dse_controloper_wshs',
        'laboriousness_on_dse_controloper_wshs_kzo',
    ];

    protected $hidden = [];

    protected $casts = [
        'date_of_create' => 'date:d.m.Y',
    ];

    public function operations()
    {
        return $this->hasMany(ptt05v02::class, 'id_v01', 'id_v01');
    }
}
