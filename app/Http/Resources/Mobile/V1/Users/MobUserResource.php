<?php

namespace App\Http\Resources\Mobile\V1\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class MobUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'username' => $this->username,
            'avatar' => $this->personal_image,
        ];
    }
}
