<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories;

use App\Src\Ship\Base\Core\Repositories\BaseRepository;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CreateCurrencyDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use Illuminate\Database\Eloquent\Collection;


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
            'return_at' => $dto->return_at,
        ]);
    }


    public function getItem()
    {

    }


    /**
     * @param string $type
     *
     * @return Collection
     */
    public function getAllFromType(string $type): Collection
    {
        return $this->startConditions()
            ->where('type', $type)->get();
    }
}
