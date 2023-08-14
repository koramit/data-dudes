<?php

namespace App\Console;

use App\Actions\AdmissionListBuilder;
use App\Actions\AdmissionListUpdater;
use App\Actions\StayListBuilder;
use App\Actions\StayListUpdater;
use App\Actions\StayOutcomeUpdater;
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
        // admissoin
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(0);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(10);
        // $schedule->call(fn () => (new AdmissionListBuilder)->run())->hourlyAt(15);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(30);
        // $schedule->call(fn () => (new AdmissionListBuilder)->run())->hourlyAt(45);
        // $schedule->call(fn () => (new AdmissionListUpdater)->run())->hourlyAt(50);

        // stay
        // $schedule->call(function () {
        //     (new StayListBuilder)->run();
        //     (new StayOutcomeUpdater)->run();
        // })->hourlyAt(0);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(6);
        // $schedule->call(function () {
        //     (new StayListBuilder)->run();
        //     (new StayListUpdater)->run();
        // })->hourlyAt(12);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(18);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(24);
        // $schedule->call(function () {
        //     (new StayListBuilder)->run();
        //     (new StayListUpdater)->run();
        // })->hourlyAt(30);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(36);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(42);
        // $schedule->call(function () {
        //     (new StayListBuilder)->run();
        //     (new StayListUpdater)->run();
        // })->hourlyAt(48);
        // $schedule->call(fn () => (new StayListBuilder)->run())->hourlyAt(54);
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
