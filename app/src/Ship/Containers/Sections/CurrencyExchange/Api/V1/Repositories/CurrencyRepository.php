<?php
declare(strict_types=1);

namespace App\src\Ship\Containers\Sections\CurrencyExchange\Api\Repositories;

use App\src\Ship\Base\Core\Repositories\BaseRepository;
use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Dto\CreateCurrencyDto;
use App\src\Ship\Containers\Sections\CurrencyExchange\Api\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Currency::class;
    }


    /**
     * @param CreateCurrencyDto $dto
     *
     * @return Currency
     */
    public function create(CreateCurrencyDto $dto): Currency
    {
        return $this->startConditions()->create([
            'currency' => $dto->currency,
            'rate' => $dto->rate,
            'name' => $dto->name,
            'type' => $dto->type,
            'nominal' => $dto->nominal,
            'num_code' => $dto->num_code,

        ]);
    }


    public function getItem()
    {

    }


    public function getAll()
    {

    }
}
