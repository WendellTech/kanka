<?php

namespace App\Console;

use App\Console\Commands\CalendarAdvancer;
use App\Console\Commands\CampaignVisibileEntityCount;
use App\Console\Commands\Cleanup\CleanupEntityLogs;
use App\Console\Commands\Cleanup\CleanupTrashed;
use App\Console\Commands\RegenerateDiscordToken;
use App\Console\Commands\Subscriptions\EndSubscriptions;
use App\Console\Commands\Subscriptions\ExpiringCardCommand;
use App\Console\Commands\Subscriptions\UpcomingYearlyCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CalendarAdvancer::class)->dailyAt('00:00');
        $schedule->command(CampaignVisibileEntityCount::class)->dailyAt('01:00');
        $schedule->command(UpcomingYearlyCommand::class)->dailyAt('06:30');
        $schedule->command(EndSubscriptions::class)->dailyAt('00:05');
        $schedule->command(RegenerateDiscordToken::class)->dailyAt('00:15');
        $schedule->command(ExpiringCardCommand::class)->monthlyOn(1, '02:00');

        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->twiceDaily(2, 14);

        $schedule->command('model:prune')->daily();
        $schedule->command(CleanupEntityLogs::class)->dailyAt('03:30');
        $schedule->command(CleanupTrashed::class)->dailyAt('02:15');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
