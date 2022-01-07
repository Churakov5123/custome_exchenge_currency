<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Cli\Services\Integrations;

use App\Src\Ship\Containers\Helpers\Xmlhelper;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CreateCurrencyDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use App\Src\Ship\Containers\Sections\Currency\Cli\Services\interfaces\BaseLoadCurrenciesService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;


class LoadEcbCurrenciesService extends BaseLoadCurrenciesService
{
    private const BASE_URL = 'https://www.ecb.europa.eu';
    protected const PREFIX = '/stats/eurofxref/eurofxref-daily.xml';
    protected const METHOD_GET = 'GET';
    private const DEFAULT_NOMINAL = 1;


    /**
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->client = new Client(['base_uri' => self::BASE_URL]);
    }


    /**
     * Полиморфизм с уникальной реализацией.
     *
     * @return array
     *
     * @throws GuzzleException
     */
    protected function prepareData(): array
    {
        $currencies = Xmlhelper::stringToArray($this->getData());

        $result = [];

        if (!empty($currencies['Cube']['Cube']['Cube'])) {
            foreach ($currencies['Cube']['Cube']['Cube'] as $currency) {
                $result[] = (new CreateCurrencyDto())->fillFromArray(
                    [
                        'currency' => $currency['@attributes']['currency'],
                        'rate' => floatval($currency['@attributes']['rate']),
                        'type' => Currency::TYPE_ECB,
                        'name' => $currency['@attributes']['currency'],
                        'nominal' => self::DEFAULT_NOMINAL,
                        'return_at' => Carbon::parse($currencies['Cube']['Cube']['@attributes']['time']),
                    ]
                );
            }
        }

        return $result;
    }
}
