<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Cli\Services\interfaces;

use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class BaseLoadCurrenciesService
{
    protected const  STATUS_SUCCESS = 200;

    protected CurrencyRepository $currencyRepository;
    protected Client $client;

    /**
     * @return array
     */
    abstract protected function prepareData(): array;


    /**
     * Точка входа.
     *
     * @return void
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
    protected function getData(): string
    {
        $response = $this->client->request(
            static::METHOD_GET,
            static::PREFIX
        );

        if (self::STATUS_SUCCESS === $response->getStatusCode()) {

            return $response->getBody()->getContents();
        }

        throw new Exception('Некорректный ответ сервера', $response->getStatusCode());
    }
}
