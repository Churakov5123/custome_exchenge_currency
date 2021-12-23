<?php

namespace App\Containers\Sections\Admin\Localization\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyExchangeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'language' => $this->language,
            'language_code' => $this->language_code,
            'available' => $this->available,
            'activity' => $this->activity,
            'domain' => $this->domain,
        ];
    }
}


