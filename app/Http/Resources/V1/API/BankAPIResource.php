<?php

namespace App\Http\Resources\V1\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'code'  => $this->code,
            'image' => $this->image,
        ];
    }
}
