<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Services;

use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CurrencyExchangeDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Presenters\ExchangePresenter;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CurrencyExchangeService
{

    private const CURRENCY_RUB = 'RUB';
    private const CURRENCY_EUR = 'EUR';

    private const CONFIGURED = [
        Currency::TYPE_ECB => self::CURRENCY_EUR,
        Currency::TYPE_CBR => self::CURRENCY_RUB,
    ];


    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Точка входа.
     *
     * @param CurrencyExchangeDto $dto
     * @return ExchangePresenter
     *
     * @throws Exception
     */
    public function handle(CurrencyExchangeDto $dto): ExchangePresenter
    {
        $result = $this->exchangeProcessing($dto);

        return new ExchangePresenter($result['take_name_currency'], $result['take_amount_currency']);
    }


    /**
     * @param CurrencyExchangeDto $dto
     *
     * @return float
     * @throws Exception
     */
    private function exchangeProcessing(CurrencyExchangeDto $dto): array
    {
        $courseData = $this->currencyRepository->getAllFromType($dto->type_source);

        // условие для базовой валюты, из конфига т.е получение в рубляхили или евро не важно.
        if (in_array($dto->take_name_currency, self::CONFIGURED)) {

            return $this->exchangeForBaseCurrency($dto, $courseData);
        }

        return $this->exchangeForNoBaseCurrency($dto, $courseData);
    }


    /**
     * Обмен на базовую валюту рубль или доллар.
     *
     * @param CurrencyExchangeDto $dto
     * @param Collection $courseData
     *
     * @return array
     * @throws Exception
     */
    private function exchangeForBaseCurrency(CurrencyExchangeDto $dto, Collection $courseData): array
    {
        $take_name_currency = self::CONFIGURED[array_search($dto->take_name_currency, self::CONFIGURED)];

        $giveCurrency = $this->getCurrency($courseData, $dto->give_name_currency);

        $amount_currency = $this->calculateForBaseCurrency($giveCurrency, $dto);

        return [
            'take_name_currency' => $take_name_currency,
            'take_amount_currency' => $amount_currency
        ];
    }


    /**
     * Обмен не на базовую валюту банка, но относительно нее.
     *
     * @param CurrencyExchangeDto $dto
     * @param Collection $courseData
     *
     * @return array
     * @throws Exception
     */
    private function exchangeForNoBaseCurrency(CurrencyExchangeDto $dto, Collection $courseData): array
    {
        $giveCurrency = $this->getCurrency($courseData, $dto->give_name_currency);

        $takeCurrency = $this->getCurrency($courseData, $dto->take_name_currency);

        $amount_currency = $this->calculateForNoBaseCurrency($giveCurrency, $takeCurrency, $dto);

        return [
            'take_name_currency' => $dto->take_name_currency,
            'take_amount_currency' => $amount_currency
        ];
    }


    /**
     * Калькулятор для базовой валюты.
     *
     * @param Currency $giveCurrency
     * @param CurrencyExchangeDto $dto
     *
     * @return float
     */
    private function calculateForBaseCurrency(Currency $giveCurrency, CurrencyExchangeDto $dto): float
    {
        $amount = ($giveCurrency->rate / $giveCurrency->nominal ?? 1) * $dto->give_count_currency;

        return round($amount, 2);
    }


    /**
     * Калькулятор не на базовую валюту банка, но относительно нее.
     *
     * @param Currency $giveCurrency
     * @param Currency $takeCurrency
     * @param CurrencyExchangeDto $dto
     *
     * @return float
     */
    private function calculateForNoBaseCurrency(Currency $giveCurrency, Currency $takeCurrency, CurrencyExchangeDto $dto): float
    {
        $amount = (($giveCurrency->rate / $giveCurrency->nominal ?? 1)
                * $dto->give_count_currency) /
            ($takeCurrency->rate / $takeCurrency->nominal ?? 1);

        return round($amount, 2);
    }


    /**
     * @param Collection $collection
     * @param string $nameCurrency
     *
     * @return Currency
     * @throws Exception
     */
    public function getCurrency(Collection $collection, string $nameCurrency): Currency
    {
        $currency = $collection->where('currency', '=', $nameCurrency)->first();

        if (empty($currency)) {

            throw new Exception('Валюта не найдена');
        }

        return $currency;
    }
}
