<?php

namespace App\Src\Ship\Containers\Sections\Currency\Cli\Commands;



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


    public function __construct(LoadCurrenciesService $loadCurrenciesService)
    {
        $this->loadCurrenciesService = $loadCurrenciesService;
        parent::__construct();
    }


    public function handle()
    {
        $start = now();
        $this->comment(self::MESSAGE_START . '-' . $start);
        $time = $start->diffInSeconds(now());

        try {
            $this->loadCurrenciesService->handle();

        } catch (Exception $ex) {
          //  Logger::logger(self::LOG_CHANNEL, $ex->getMessage(), $ex->getTrace());
        }

        $this->info(
            sprintf(self::MESSAGE_FINISH . '%s сек', $time)
        );
    }
}
