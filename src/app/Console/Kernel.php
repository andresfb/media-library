<?php

namespace App\Console;

use App\Jobs\GenerateFeedJob;
use App\Jobs\GeneratePostJob;
use App\Jobs\ImportMediaJob;
use App\Services\AvatarGeneratorService;
use App\Services\GenerateFeedService;
use App\Services\ImportMediaService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new ImportMediaJob(new ImportMediaService()), 'ingestor')
            ->wednesdays()
            ->at('16:00');

        $schedule->job(new GeneratePostJob(), 'default')
            ->cron("15 11,15,19,23 * * *");

        $schedule->job(
            new GenerateFeedJob(new GenerateFeedService(new AvatarGeneratorService())),
            'default'
        )->dailyAt("12:00");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
