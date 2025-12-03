<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'p006' => $this->p006,
            'code_detail' => preg_replace("/^(\d{4})(\d{6})(\d{3})(\d{2})/u", "$1.$2.$3-$4", $this->p003),
            'p0081' => $this->p0081
        ];
    }
}
