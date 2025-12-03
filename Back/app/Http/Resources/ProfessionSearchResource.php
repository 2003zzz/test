<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionSearchResource extends JsonResource
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
            'profcode70' => $this->profcode70,
            'snm_as_scaption' => $this->snm_as_scaption,
            'type_of_profession_reference_book' => $this->kategoryprof,
        ];
    }
}
