<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Controllers;


use App\Http\Controllers\Controller;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CurrencyExchangeDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Requests\CurrencyExchangeRequest;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Resources\CurrencyExchangeResource;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Services\CurrencyExchangeService;


class CurrencyExchangeController extends Controller
{
    private CurrencyExchangeService $currencyExchangeService;

    public function __construct(CurrencyExchangeService $currencyExchangeService)
    {
        $this->currencyExchangeService = $currencyExchangeService;
    }


    /**
     *
     * @param CurrencyExchangeRequest $request
     *
     * @return CurrencyExchangeResource
     */
    public function exchange(CurrencyExchangeRequest $request): CurrencyExchangeResource
    {
        $dto = (new CurrencyExchangeDto)->fillFromRequest($request);

        $result = $this->currencyExchangeService->exchange($dto);

        return new CurrencyExchangeResource($result);
    }
}
