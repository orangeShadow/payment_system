<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id'          => $this->id,
            'user_from'   => $this->whenLoaded('purseFrom', function () {
                if (!empty($this->purseFrom)) {
                    return $this->purseFrom->user->name;
                }
            }),
            'user_to'     => $this->whenLoaded('purseTo', function () {
                if (!empty($this->purseTo)) {
                    return $this->purseTo->user->name;
                }
                return 't';
            }),
            'purse_from'  => $this->purse_from,
            'purse_to'    => $this->purse_to,
            'amount_from' => $this->amount_from,
            'amount_to'   => $this->amount_to,
            'amount_usd'  => $this->amount_usd,
        ];
    }
}
