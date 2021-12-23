<?php
declare(strict_types=1);

namespace App\src\Ship\Containers\Sections\CurrencyExchange\Api\Controllers;

use App\Containers\Sections\Admin\Localization\Resources\CurrencyExchangeResource;
use App\src\Ship\Base\Core\Controllers\BaseController;
use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Dto\CurrencyExchangeDto;
use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Requests\CurrencyExchangeRequest;
use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Services\CurrencyExchangeService;

class CurrencyExchangeController extends BaseController
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
