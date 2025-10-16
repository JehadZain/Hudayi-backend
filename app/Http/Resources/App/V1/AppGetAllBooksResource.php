<?php

namespace App\Http\Resources\App\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppGetAllBooksResource extends JsonResource
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
            'name' => $this->name,
            'author_name' => $this->author_name,
        ];
    }
}
