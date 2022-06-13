<?php /** @noinspection DuplicatedCode */

namespace App\Core\Players\Jobs;

use App\Core\Players\Services\RenewContractPlayersService;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use Cirelramos\Logs\Facades\LogConsoleFacade;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class RenewContractPlayersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private RenewContractPlayersService $renewContractPlayersService)
    {
    }

    /**
     *
     */
    public function handle(): void
    {
        try {
            $array                 = ['message' => "start " . __CLASS__ . " "];
            LogConsoleFacade::simple()->log( 'job', $array );

            $this->renewContractPlayersService->execute();

            $array = ['message' => "finish " . __CLASS__ . " "];
            LogConsoleFacade::simple()->log( 'job', $array );
        } catch (Exception $exception) {
            $context = [
                'url'       => '',
                'exception' => $exception,
            ];
            CatchNotificationService::error($context);

            throw new Exception($exception->getMessage(),  previous:$exception);
        }
    }
}
