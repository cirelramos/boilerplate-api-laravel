<?php

namespace App\Core\Players\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Cirelramos\RawQuery\Facades\RawQuery;

/**
 *
 */
class CheckPlayersRankService
{
    public function execute(): void
    {
        try {
            DB::beginTransaction();
            $value = " select * from players  WHERE 1 ";
            RawQuery::fetchAll($value);
            $value = "UPDATE players t SET t.active = 0 WHERE t.rank < 5";
            RawQuery::query($value);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage(), previous: $exception);
        }
    }
}
