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
use Psr\Http\Message\StreamInterface;

class LoadEcbCurrenciesService
{
    const SUCCESS_STATUS_SUCCESS = 200;

    private CurrencyRepository $currencyRepository;
    private Client $client;

    /**
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->client = new Client(['base_uri' => 'https://www.ecb.europa.eu']);
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
     * @return StreamInterface
     *
     * @throws GuzzleException
     */
    private function getData(): StreamInterface
    {
        $response = $this->client->request(
            'GET',
            '/stats/eurofxref/eurofxref-daily.xml'
        );

        if (self::SUCCESS_STATUS_SUCCESS === $response->getStatusCode()) {

            return $response->getBody();
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
        $currencies = Xmlhelper::toArray($this->getData());

        $result = [];

        if (!empty($currencies)) {
            foreach ($currencies as $currency) {
                $result[] = (new CreateCurrencyDto())->fillFromArray(
                    [
                        'currency' => $currency['currency'],
                        'rate' => $currency['rate'],
                        'type' => Currency::TYPE_ECB,
                        'name' => $currency['name'],
                        'nominal' => $currency['nominal'],
                        'num_code' => $currency['num_code']
                    ]
                );
            }
        }

        return $result;
    }
}
