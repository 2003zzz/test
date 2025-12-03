<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card.id_v01' => 'required',
            'card.workshop' => 'required',
            'card.designation' => 'required',
            'card.code_detail' => 'required',
            'card.note' => '',
            'card.cipher_main_td' => '',
            'card.type_technical_doc' => '',
            'card.number_technological_notification' => '',
            'card.party' => '',

            'operations.*.id_v01' => '',
            'operations.*.id_v02' => 'nullable',
            'operations.*.time_rate_is_paid' => 'required',
            'operations.*.unit_of_measurement' => 'required',
            'operations.*.end_to_end_operation_number' => 'required',
            'operations.*.operation_number' => '',
            'operations.*.norm_of_cycle_time' => '',
            'operations.*.launch_ratio' => '',
            'operations.*.cipher_of_the_operation' => 'required',
            'operations.*.hardware_cipher' => '',
            'operations.*.cipher_of_the_profession' => 'required',
            'operations.*.type_of_profession_reference_book' => 'required',
            'operations.*.cipher_of_the_reference_tp' => '',
            'operations.*.code_of_the_tariff_grid' => 'required',
            'operations.*.category_of_work' => 'required',
            'operations.*.number_notification_sgt' => '',
            'operations.*.type_of_norms' => 'required',
            'operations.*.unit_of_the_rationong' => 'required',
            'operations.*.number_of_worker' => '',
            'operations.*.operation_as_needed' => '',
            'operations.*.operation_for_execution' => '',
            'operations.*.operation_with_technological_shutdowns' => '',
            'operations.*.operations_for_samples' => '',
        ];
    }

    protected function prepareForValidation(): void
    {
        [$cipher, $type] = array_pad(explode('/', $this->cipher_main_TD), 2, '');

        $formattedOperations = array_map(function ($operation) {
            $operation['time_rate_is_paid'] = str_replace(',', '.', $operation['time_rate_is_paid']);
            $operation['norm_of_cycle_time'] = str_replace(',', '.', $operation['norm_of_cycle_time']);
            $operation['hardware_cipher'] = preg_replace('/[^\d]/ui', '', $operation['hardware_cipher']);

            return $operation;
        }, $this->operations);

        $this->merge([
            'cipher_main_td' => trim($cipher) ?: null,
            'type_technical_doc' => trim($type) ?: null,
            'designation' => preg_replace('/[^А-я\d]/ui', '', $this->designation),
            'code_detail' => preg_replace('/[^А-я\d]/ui', '', $this->code_detail),
            'operations' => $formattedOperations,
        ]);
    }
}
