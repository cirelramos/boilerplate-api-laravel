<?php /** @noinspection DuplicatedCode */

namespace App\Core\Players\Jobs;

use App\Core\Players\Services\RenewContractPlayersService;
use Cirelramos\Cache\Services\SetHeaderSwitchActiveRecordAndModeJobService;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Cirelramos\Logs\Facades\LogConsoleFacade;
use Illuminate\Support\Carbon;

/**
 *
 */
class RenewContractPlayersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private                             $typeJob,
        private                             $disableQuery,
        private RenewContractPlayersService $renewContractPlayersService
    ) {
        SetHeaderSwitchActiveRecordAndModeJobService::execute($this->disableQuery, $this->typeJob);
    }

    /**
     *
     */
    public function handle(): void
    {
        try {
            request()->job_name_and_timeline = __CLASS__ . "_" . Carbon::now()->format('Y_m_d_H_i_s');
            LogConsoleFacade::simple()->log("start job " . __CLASS__ );

            $this->renewContractPlayersService->execute();

            LogConsoleFacade::simple()->log("finish job " . __CLASS__ );
        } catch (Exception $exception) {
            $context = [
                'url'       => '',
                'exception' => $exception,
            ];
            CatchNotificationService::error($context);
            throw new Exception($exception->getMessage(), previous: $exception);
        }
    }
}
