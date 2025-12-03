<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DuplicateOperationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cardIds' => 'array|required',
            'operation' => 'array|required',

            'operation.time_rate_is_paid' => 'required',
            'operation.unit_of_measurement' => 'required',
            'operation.end_to_end_operation_number' => 'required',
            'operation.operation_number' => '',
            'operation.norm_of_cycle_time' => '',
            'operation.launch_ratio' => '',
            'operation.cipher_of_the_operation' => 'required',
            'operation.hardware_cipher' => '',
            'operation.cipher_of_the_profession' => 'required',
            'operation.type_of_profession_reference_book' => 'required',
            'operation.cipher_of_the_reference_tp' => '',
            'operation.code_of_the_tariff_grid' => 'required',
            'operation.category_of_work' => 'required',
            'operation.number_notification_sgt' => '',
            'operation.type_of_norms' => 'required',
            'operation.unit_of_the_rationong' => 'required',
            'operation.number_of_worker' => '',
            'operation.operation_as_needed' => '',
            'operation.operation_for_execution' => '',
            'operation.operation_with_technological_shutdowns' => '',
            'operation.operations_for_samples' => '',
        ];
    }
}
