<?php

namespace App\Core\Players\Services;

use App\Core\Players\Models\Player;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class RenewContractPlayersService
{
    public function execute(): void
    {
        try {
            DB::beginTransaction();
            Player::where('renew', '=', 1)
                ->where('renew_at', '<=', Carbon::now())
                ->update(['updated_at' => Carbon::now(),]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            CatchNotificationService::error(['exception' => $exception,]);
        }
    }
}
