<?php

namespace App\Http\Requests;

use App\Services\CommonService;
use Illuminate\Foundation\Http\FormRequest;

class CreateCardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }



    public function rules(): array
    {
        return [
            'workshop' => 'bail|required|integer|max:999',
            'designation' => 'required',
            'code_detail' => 'required',
            'cipher_main_td' => 'bail|required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'workshop.required' => 'Поле "Цех" обязательно для заполнения',
            'workshop.integer' => 'Поле "Цех" принимает только целочисленные значения',
            'workshop.max' => 'Поле "Цех" имеет неверное значение',
            'designation.required' => 'Поле "Индекс" обязательно для заполнения',
            'code_detail.required' => 'Поле "Код" обязательно для заполнения',
            'cipher_main_.required' => 'Поле "ТД" обязательно для заполнения',
            'cipher_main_td.numeric' => 'Поле "ТД" принимает только численные значения',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'designation' => preg_replace('/[^А-я\d]/ui', '', $this->designation),
            'code_detail' => preg_replace('/[^А-я\d]/ui', '', $this->code_detail),
            'service_number' => app(CommonService::class)->getCurrentUser()["tabNum"],
            'create_service_number' => app(CommonService::class)->getCurrentUser()["tabNum"],
        ]);
    }
}
