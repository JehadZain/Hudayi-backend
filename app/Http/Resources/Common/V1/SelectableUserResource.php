<?php

namespace App\Http\Resources\Common\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectableUserResource extends JsonResource
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
            'value' => $this->user->id,
            'label' => $this->user->first_name ? $this->user->first_name.' '.$this->user->last_name : 'no name',
            'subtitle' => $this->user->username,
            'img' => $this->user->personal_image,
        ];
    }
}
