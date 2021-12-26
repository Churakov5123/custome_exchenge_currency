<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Sections\Currency\Cli\Commands;

use App\Src\Ship\Containers\Sections\Currency\Api\V1\Models\Currency;
use App\Src\Ship\Containers\Sections\Currency\Api\V1\Repositories\CurrencyRepository;
use App\Src\Ship\Containers\Sections\Currency\Cli\Services\LoadCurrenciesService;
use Exception;
use Illuminate\Console\Command;

class UpdatingCurrencies extends Command
{
    private const LOG_CHANNEL = 'updating-currencies';
    private const MESSAGE_START = 'Начало процедуры получения текущих курсов валют';
    private const MESSAGE_FINISH = 'Процедура успешно окончена и составила ';

    private LoadCurrenciesService $loadCurrenciesService;

    protected $signature = 'currency:updating-currencies';
    protected $description = 'Command description';
    private CurrencyRepository $currencyRepository;


    public function __construct(LoadCurrenciesService $loadCurrenciesService, CurrencyRepository $currencyRepository)
    {
        $this->loadCurrenciesService = $loadCurrenciesService;
        $this->currencyRepository = $currencyRepository;
        parent::__construct();
    }


    public function handle()
    {
        $start = now();
        $this->comment(self::MESSAGE_START . '-' . $start);

        $time = $start->diffInSeconds(now());

        try {
            $this->loadCurrenciesService->handle();

            $this->info(
                sprintf(self::MESSAGE_FINISH . '%s сек', $time)
            );
        } catch (Exception $ex) {
            //  Logger::logger(self::LOG_CHANNEL, $ex->getMessage(), $ex->getTrace());
            $this->info(
                sprintf(self::MESSAGE_FINISH . '%s сек', $time)
            );
        }
    }
}
