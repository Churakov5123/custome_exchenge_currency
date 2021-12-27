<?php

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyExchangeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'take_name_currency' => $this->take_name_currency,
            'take_amount_currency' => $this->take_amount_currency
        ];
    }
}


