<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Presenters;

use Illuminate\Database\Eloquent\Model;

/**
 * Подгтовленне представление валюты которую обменяли.
 */
class ExchangePresenter extends Model
{
    public string $take_name_currency;

    public float $take_amount_currency;


    /**
     * @param string $take_name_currency
     * @param float $take_amount_currency
     */
    public function __construct(string $take_name_currency, float $take_amount_currency)
    {
        $this->take_name_currency = $take_name_currency;
        $this->take_amount_currency = $take_amount_currency;

        parent::__construct();
    }


}
