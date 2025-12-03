<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ptt05v02 extends Model
{
    use HasFactory;
    protected $table = 'ptt05.ptt05v02';

    protected $primaryKey = 'id_v02';

    public $timestamps = false;

    protected $attributes = [];

    protected $fillable = [
        "id_v01",
        "end_to_end_operation_number",
        "cipher_of_the_operation",
        "cipher_of_the_profession",
        "category_of_work",
        "hardware_cipher",
        "type_of_norms",
        "code_of_the_tariff_grid",
        "unit_of_the_rationong",
        "time_rate_is_paid",
        "unit_of_measurement",
        "launch_ratio",
        "operation_number",
        "cipher_of_the_reference_tp",
        "norm_of_cycle_time",
        "operation_as_needed",
        "number_of_worker",
        "operations_for_samples",
        "note",
        "number_parts_of_detail",
        "number_notification_sgt",
        "aria",
        "id_version",
        "type_of_profession_reference_book",
        "operation_with_technological_shutdowns",
        "operation_for_execution",
        "pricing",
        "billing_time_kzo",
        "cycle_time_kzo",
        "pricing_kzo",
        'pr_generating_operation'
    ];

    protected $hidden = [];

    protected $casts = [
        'date_entry_operation' => 'date:d.m.Y',
    ];

    public function card()
    {
        return $this->belongsTo(ptt05v01::class, 'id_v02', 'id_v02');
    }

    public function scopeWithOperationName($query)
    {
        $query
            ->addSelect('pak65.pak65e045.p014')
            ->leftJoin('pak65.pak65e045', 'pak65.pak65e045.p018', DB::Raw("CAST(ptt05v02.cipher_of_the_operation AS INTEGER)"));
    }

    public function scopeWithHardwareName($query)
    {
        $query
            ->addSelect('pak01.pak01e01.p451')
            ->leftJoin('pak01.pak01e01', 'pak01.pak01e01.p501', DB::Raw("CONCAT('0000', ptt05v02.hardware_cipher)"));
    }

    public function scopeWithProfessionName($query)
    {
        $query
            ->addSelect('pkt50_v.snm_as_scaption')
            ->leftJoin('pkt50_v', function (JoinClause $join) {
                $join
                    ->on('pkt50_v.profcode70', 'ptt05v02.cipher_of_the_profession')
                    ->on('pkt50_v.kategoryprof', 'ptt05v02.type_of_profession_reference_book');
            });
    }
}
