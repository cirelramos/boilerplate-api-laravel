<?php

namespace App\Core\Teams\Services;

use App\Core\Teams\Models\RegionRapi;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Cirelramos\ErrorNotification\Services\CatchNotificationService;

/**
 *
 */
class UpdateRecordRegionRapiService
{
    public function execute(): void
    {
        try {
            DB::beginTransaction();
            RegionRapi::where('name', '!=', '')->update(['updated_at' => Carbon::now()]);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            CatchNotificationService::error([ 'exception' => $exception, ]);
        }
    }
}
