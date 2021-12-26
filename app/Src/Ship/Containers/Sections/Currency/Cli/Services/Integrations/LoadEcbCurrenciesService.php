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


class LoadEcbCurrenciesService
{
    const SUCCESS_STATUS_SUCCESS = 200;
    const BASE_URL = 'https://www.ecb.europa.eu';
    const PREFIX = '/stats/eurofxref/eurofxref-daily.xml';
    const METHOD_GET = 'GET';
    const DEFAULT_NOMINAL = 1;

    private CurrencyRepository $currencyRepository;
    private Client $client;

    /**
     * @param CurrencyRepository $currencyRepository
     */
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
