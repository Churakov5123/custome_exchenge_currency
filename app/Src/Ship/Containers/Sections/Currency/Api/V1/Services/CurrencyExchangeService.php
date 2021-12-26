<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Api\V1\Services;

use App\Src\Ship\Containers\Sections\Currency\Api\V1\Dto\CurrencyExchangeDto;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use Illuminate\Database\Eloquent\Collection;

class CurrencyExchangeService
{
    public const SOURCES_CURRENCY = Currency::TYPE_ECB;

    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }


    public function handle(CurrencyExchangeDto $dto): float
    {
        return $this->exchangeProcessing($dto);
    }

    private function exchangeProcessing(CurrencyExchangeDto $dto): float
    {
        $courseData = $this->currencyRepository->getAllFromType(self::SOURCES_CURRENCY);

        $giveNameCourse = $this->getСourse($courseData, $dto->give_name_currency);

        $takeNameCourse = $this->getСourse($courseData, $dto->take_name_currency);

        return $this->calculateCurrency($giveNameCourse, $takeNameCourse, $dto->give_count_currency);
    }

    private function exchangeFromBaseCurrency()
    {
// это в случае с доларок рублю
//        $give_name;
//        $give_name_count;
//
//        $take_name;

//        $give_name_count * $take_name_course;
//
//        //рубль к долару
//        $give_name_count / $take_name

        //евро к долару например отностельно рубля считается так
       // ($give_name_course_in_ruble * $give_name_count) / $take_name_course_in_ruble) =так мы получаем количество валют

    }


    private function exchangeFromNotBaseCurrency()
    {

    }

    public function getСourse(Collection $collection, string $nameCurrency)
    {


    }

    public function calculateCurrency(float $giveNameCourse, float $takeNameCourse, int $give_count_currency)
    {

        //логика



    }
}
