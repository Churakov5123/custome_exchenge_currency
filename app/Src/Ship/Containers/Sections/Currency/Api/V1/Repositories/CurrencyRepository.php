<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories;

use App\Src\Ship\Base\Core\Repositories\BaseRepository;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CreateCurrencyDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;


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
