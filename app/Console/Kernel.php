<?php

namespace App\Console;

use App\Console\Commands\ProcessMasiveEmails;
use App\Console\Commands\ReportProductsCreatedByDay;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        ReportProductsCreatedByDay::class,
        ProcessMasiveEmails::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:report-products-created-by-day')->dailyAt('21:30');
        $schedule->command('app:process-masive-emails')->everyTwoMinutes();
        /* $schedule->command('app:report-products-created-by-day')->everyMinute(); */
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
