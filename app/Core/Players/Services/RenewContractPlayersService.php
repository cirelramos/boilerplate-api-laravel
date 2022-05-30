<?php

namespace App\Core\Players\Services;

use App\Core\Players\Models\Player;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;
use Exception;
use Http\Client\Exception\HttpException;
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
            $update = [
                'renew_next' => null,
                'updated_at' => Carbon::now(),
                'renew_at'   => Carbon::now(),
                'renew'      => 0,
            ];
           Player::where('renew', '=', 1)
                ->where('renew_next', '>=', Carbon::now())
                ->update($update)
            ;
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            // CatchNotificationService::error(['exception' => $exception,]);
            throw new Exception($exception->getMessage(),  previous:$exception);
        }
    }
}
