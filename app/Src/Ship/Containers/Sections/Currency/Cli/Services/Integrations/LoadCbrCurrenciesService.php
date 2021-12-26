<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Cli\Services\Integrations;

use App\Src\Ship\Containers\Helpers\Xmlhelper;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CreateCurrencyDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;

class LoadCbrCurrenciesService
{
    const SUCCESS_STATUS_SUCCESS = 200;
    const BASE_URL = 'https://www.cbr.ru';
    const PREFIX = '/scripts/XML_daily.asp';
    const METHOD_GET = 'GET';

    private CurrencyRepository $currencyRepository;
    private Client $client;


    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->client = new Client(['base_uri' => self::BASE_URL]);
    }


    /**
     * @return void
     *
     * @throws GuzzleException
     */
    public function execute(): void
    {
        foreach ($this->prepareData() as $item) {
            $this->currencyRepository->create($item);
        }
    }


    /**
     * @return string
     *
     * @throws GuzzleException
     */
    private function getData(): string
    {
        $response = $this->client->request(
            self::METHOD_GET,
            self::PREFIX
        );

        if (self::SUCCESS_STATUS_SUCCESS === $response->getStatusCode()) {

            return $response->getBody()->getContents();
        }

        throw new Exception('Некорректный ответ сервера', $response->getStatusCode());
    }


    /**
     * @return array
     *
     * @throws GuzzleException
     */
    private function prepareData(): array
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
