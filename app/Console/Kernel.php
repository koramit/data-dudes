<?php

namespace App\Console;

use App\Actions\AdmissionListBuilder;
use App\Actions\AdmissionListUpdater;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(0);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(10);
        // $schedule->call(fn () => (new AdmissionListBuilder)->run())->hourlyAt(15);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(30);
        // $schedule->call(fn () => (new AdmissionListBuilder)->run())->hourlyAt(45);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(50);
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
