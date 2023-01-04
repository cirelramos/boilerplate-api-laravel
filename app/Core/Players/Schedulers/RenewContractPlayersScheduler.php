<?php

namespace App\Core\Players\Schedulers;

use App\Core\Players\Jobs\RenewContractPlayersJob;
use App\Core\Players\Services\RenewContractPlayersService;
use Cirelramos\Cache\Schedulers\CustomCommand;

/**
 *
 */
class RenewContractPlayersScheduler extends CustomCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players:renew-contracts {type_job} {mode_record}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'renew contract players, php artisan players:renew-contracts async enable_record';

    public function __construct(private RenewContractPlayersService $renewContractPlayersService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $typeJob      = $this->argument('type_job');
        $disableQuery = $this->argument('mode_record');
        $job          = new RenewContractPlayersJob(
            $typeJob,
            $disableQuery,
            $this->renewContractPlayersService
        );
        $this->dispatchJob($job, config('queue-names.general'));
    }
}
