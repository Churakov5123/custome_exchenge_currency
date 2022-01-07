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

class LoadCbrCurrenciesService extends BaseLoadCurrenciesService
{
    private const BASE_URL = 'https://www.cbr.ru';
    protected const PREFIX = '/scripts/XML_daily.asp';
    protected const METHOD_GET = 'GET';


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

        if (!empty($currencies['Valute'])) {

            foreach ($currencies['Valute'] as $currency) {
                $result[] = (new CreateCurrencyDto())->fillFromArray(
                    [
                        'currency' => $currency['CharCode'],
                        'rate' => floatval(str_replace(',', '.', $currency['Value'])),
                        'type' => Currency::TYPE_CBR,
                        'name' => $currency['Name'],
                        'nominal' => (int)$currency['Nominal'],
                        'num_code' => (int)$currency['NumCode'],
                        'return_at' => Carbon::parse($currencies['@attributes']['Date']),
                    ]
                );
            }
        }

        return $result;
    }
}
