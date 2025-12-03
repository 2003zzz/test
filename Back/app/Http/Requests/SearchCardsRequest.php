<?php

namespace App\Http\Requests;

use Carbon\Carbon;

use Illuminate\Foundation\Http\FormRequest;

class SearchCardsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['required', 'integer'],
            'per_page' => ['integer', 'max:100'],
            'sort_by' => ['string', 'in:designation,code_detail,name,workshop,cipher_main_td,norm,created_date,updated_date,status'],
            'sort_direction' => ['string', 'in:asc,desc'],

            'query' => ['required_without:search', 'string'],

            'search' => ['required_without:query', 'array'],

            'search.designation' => ['string', 'nullable'],
            'search.code_detail' => ['string', 'nullable'],
            'search.name' => ['string', 'nullable'],
            'search.workshop' => ['string', 'nullable'],
            'search.cipher_main_td' => ['string', 'nullable'],
            'search.cipher_of_the_reference_tp' => ['string', 'nullable'],
            'search.norm' => ['string', 'nullable'],
            'search.dateCreatedFrom' => ['date', 'nullable'],
            'search.dateCreatedTo' => ['date', 'nullable'],
            'search.dateEditedFrom' => ['date', 'nullable'],
            'search.dateEditedTo' => ['date', 'nullable'],
        ];
    }

    public function attributes(): array
    {
        return [
            'search.designation' => 'Индекс',
            'search.code_detail' => 'Код',
            'search.name' => 'Наименование',
            'search.workshop' => 'Цех',
            'search.cipher_main_td' => '№ основного ТП',
            'search.cipher_of_the_reference_tp' => '№ ссылочного ТП',
            'search.norm' => 'Нормировщик',
            'search.dateCreatedFrom' => 'Дата заведения (дата с)',
            'search.dateCreatedTo' => 'Дата заведения (дата по)',
            'search.dateEditedFrom' => 'Дата изменения (дата с)',
            'search.dateEditedTo' => 'Дата изменения (дата по)',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения',
            'string' => 'Поле :attribute должно быть строкой',
            'date' => 'Поле :attribute должно быть датой',
            'array' => 'Поле :attribute должно быть массивом',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('query')) {
            $merge['query'] = preg_replace('/[^А-я\d]/ui', '', $this->get('query'));
        } else {
            $merge['designation'] = preg_replace('/[^А-я\d]/ui', '', $this->designation);
            $merge['code_detail'] = preg_replace('/[^\d]/ui', '', $this->code_detail);
            $merge['name'] = preg_replace('/[^А-я\d]/ui', '', $this->name);
            $merge['dateCreatedFrom'] = Carbon::parse($this->dateCreatedFrom)->startOfDay()->format('Y-m-d H:i:s');
            $merge['dateCreatedTo'] = Carbon::parse($this->dateCreatedTo)->startOfDay()->format('Y-m-d H:i:s');
            $merge['dateEditedFrom'] = Carbon::parse($this->dateEditedFrom)->startOfDay()->format('Y-m-d H:i:s');
            $merge['dateEditedTo'] = Carbon::parse($this->dateEditedTo)->startOfDay()->format('Y-m-d H:i:s');
        }
        $this->merge($merge);
    }
}
