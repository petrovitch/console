<?php

namespace App\Console;

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
		\App\Console\Commands\Comments::class,
		\App\Console\Commands\Controller::class,
		\App\Console\Commands\DatabaseSeeder::class,
		\App\Console\Commands\Inspire::class,
		\App\Console\Commands\MigrateMysqlSchemaCommand::class,
		\App\Console\Commands\Migrations::class,
		\App\Console\Commands\Model::class,
		\App\Console\Commands\PhpInfo::class,
		\App\Console\Commands\PhpUnits::class,
		\App\Console\Commands\RegisterCommands::class,
		\App\Console\Commands\Schema::class,
		\App\Console\Commands\Seeder::class,
		\App\Console\Commands\UpdateModel::class,
		\App\Console\Commands\Versions::class,
	];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
            ->hourly();
    }
}