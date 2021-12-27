<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto;

use App\Src\Ship\Base\Core\Dto\BaseDto;

class CurrencyExchangeDto extends BaseDto
{
    public string $give_name_currency;
    public int $give_count_currency;
    public string $take_name_currency;
    public string $type_source;


    protected function className(): string
    {
        return self::class;
    }
}
