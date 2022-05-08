<?php

namespace App\Core\Teams\Schedulers;

use App\Core\Teams\Jobs\UpdateRecordRegionRapiJob;
use App\Core\Teams\Services\UpdateRecordRegionRapiService;
use Illuminate\Console\Command;

/**
 *
 */
class UpdateRecordRegionRapiScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region-rapi:update-date-record';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update date of record region-rapi';

    public function __construct(private UpdateRecordRegionRapiService $updateRecordRegionRapiService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $job = new UpdateRecordRegionRapiJob($this->updateRecordRegionRapiService);
        dispatch($job)->onQueue(config('queue-names.general'));
    }
}
