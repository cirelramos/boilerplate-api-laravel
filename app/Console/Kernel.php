<?php

namespace App\Console;

use App\Core\Players\Schedulers\RenewContractPlayersScheduler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Cirelramos\ErrorNotification\Schedulers\SendGroupNotificationScheduler;

/**
 *
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        SendGroupNotificationScheduler::class,
        RenewContractPlayersScheduler::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('error-notification:send-group-notification')
            ->withoutOverlapping()
            ->everyFiveMinutes()
            ->sendOutputTo('/dev/stderr');

        $schedule->command('players:renew-contracts async enable_record')
            ->withoutOverlapping()
            ->everyMinute()
            ->sendOutputTo('/dev/stderr');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
