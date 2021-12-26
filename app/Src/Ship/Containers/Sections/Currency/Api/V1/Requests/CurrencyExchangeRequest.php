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
            'give_name_currency' => ['required', 'string', 'max:10'],
            'give_count_currency' => ['required', 'integer'],
            'take_name_currency' => ['required', 'string', 'max:10'],
        ];
    }

}
