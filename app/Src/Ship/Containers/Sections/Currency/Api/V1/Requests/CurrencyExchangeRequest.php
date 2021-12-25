<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyExchangeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
        ];
    }

}
