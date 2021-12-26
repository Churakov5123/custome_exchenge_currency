<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto;

use App\Src\Ship\Base\Core\Dto\BaseDto;
use Carbon\Carbon;

class CreateCurrencyDto extends BaseDto
{
    public string $currency;
    public float $rate;
    public string $type;
    public ?string $name = null;
    public ?int $nominal = null;
    public ?int $num_code = null;
    public Carbon $return_at;


    protected function className(): string
    {
        return self::class;
    }
}
