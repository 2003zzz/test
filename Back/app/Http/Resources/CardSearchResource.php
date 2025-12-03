<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardSearchResource extends JsonResource
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
            'id_v01' => $this->id_v01,
            'designation' => $this->designation,
            'code_detail' => $this->code_detail,
            'name' => $this->name,
            'workshop' => $this->workshop,
            'cipher_main_td' => $this->cipher_main_td,
            'norm' => $this->norm,
            'created_date' => date('d.m.Y', strtotime($this->created_date)),
            'updated_date' => ($this->updated_date ? date('d.m.Y', strtotime($this->updated_date)) : null),
            'status' => $this->status,
        ];
    }
}
