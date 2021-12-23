<?php
declare(strict_types=1);

namespace App\src\Ship\Containers\Sections\CurrencyExchange\Api\Requests;

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
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1'],
            'field' => ['required', 'string', 'max:255'],
            'order' => ['required', 'string', 'in:asc,desc'],
        ];
    }

}
