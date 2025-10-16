<?php

namespace App\Http\Resources\Common\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectableResource extends JsonResource
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
            'value' => $this->id,
            'label' => $this->name || $this->label,
            'img' => $this->img,
        ];
    }
}
