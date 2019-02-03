<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'currency'   => $this->currency,
            'amount'     => $this->amount,
            'created_at' => $this->created_at,
            'user'       => $this->whenLoaded('user')
        ];
    }
}
