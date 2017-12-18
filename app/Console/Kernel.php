<?php

namespace Zhiyi\Plus\Console;

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
        Commands\PackageCreateCommand::class,
        Commands\PackageArchiveCommand::class,
        Commands\PackageLinkCommand::class,
        Commands\PackageHandlerCommand::class,
        Commands\InstallPasswordCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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

    /**
     * Get the Artisan application instance.
     *
     * @return \Illuminate\Console\Application
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function getArtisan()
    {
        $artisan = parent::getArtisan();
        $artisan->setName(sprintf('ThinkSNS Plus ( For Larvel %s )', $this->app->getLaravelVersion()));

        return $artisan;
    }
}
