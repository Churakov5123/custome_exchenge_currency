<?php
declare(strict_types=1);

namespace App\src\Ship\Containers\Sections\CurrencyExchange\Cli\Services;

use App\src\Ship\Containers\Sections\CurrencyExchange\Cli\Services\Integrations\LoadCbrCurrenciesService;
use App\src\Ship\Containers\Sections\CurrencyExchange\Cli\Services\Integrations\LoadEcbCurrenciesService;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Базовый класс который консолидирует логику связанную с импортом курсов валют из каждого источника.
 */
class LoadCurrenciesService
{
    private LoadCbrCurrenciesService $loadCbrCurrenciesService;
    private LoadEcbCurrenciesService $loadEcbCurrenciesService;

    /**
     * @param LoadCbrCurrenciesService $loadCbrCurrenciesService
     * @param LoadEcbCurrenciesService $loadEcbCurrenciesService
     */
    public function __construct(
        LoadCbrCurrenciesService $loadCbrCurrenciesService,
        LoadEcbCurrenciesService $loadEcbCurrenciesService
    ){
        $this->loadCbrCurrenciesService = $loadCbrCurrenciesService;
        $this->loadEcbCurrenciesService = $loadEcbCurrenciesService;
    }


    /**
     * @return void
     *
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $this->loadCbrCurrenciesService->execute();

        $this->loadEcbCurrenciesService->execute();
    }
}
