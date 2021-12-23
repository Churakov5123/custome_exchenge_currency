<?php
declare(strict_types=1);
namespace App\src\Ship\Containers\Sections\CurrencyExchange\Api\Dto;

use App\src\Ship\Base\Core\Dto\BaseDto;

class CurrencyExchangeDto extends BaseDto
{
    public string $currency;
    public float $rate;
    public string $type;
    public ?string $name = null;
    public ?int $nominal = null;
    public ?int $num_code = null;


    protected function className(): string
    {
        return self::class;
    }
}
