<?php

namespace App\Core\Players\Services;

use App\Core\Players\Jobs\CheckPlayersRankJob;
use App\Core\Players\Models\Player;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Cirelramos\Cache\Models\ModelCacheConst;
use Cirelramos\Cache\Services\DispatchJobService;

/**
 *
 */
class RenewContractPlayersService
{

    public function __construct(private CheckPlayersRankService $checkPlayersRankService) { }

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
                ->updateWithCache($update);
            DB::commit();

            $typeJob      = request()->header(ModelCacheConst::HEADER_MODE_JOB);
            $disableQuery = request()->header(ModelCacheConst::HEADER_ACTIVE_RECORD);
            $job          = new CheckPlayersRankJob($typeJob, $disableQuery, $this->checkPlayersRankService);
            DispatchJobService::execute($job, config('queue-names.general'));
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage(), previous: $exception);
        }
    }
}
