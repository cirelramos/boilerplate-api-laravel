<?php

namespace App\Core\Players\Schedulers;

use App\Core\Players\Jobs\RenewContractPlayersJob;
use App\Core\Players\Services\RenewContractPlayersService;
use Illuminate\Console\Command;

/**
 *
 */
class RenewContractPlayersScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players:renew-contracts';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'renew contract players';

    public function __construct(private RenewContractPlayersService $renewContractPlayersService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $job = new RenewContractPlayersJob($this->renewContractPlayersService);
        dispatch($job)->onQueue(config('queue-names.general'));
    }
}
