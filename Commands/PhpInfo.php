<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/*
Argument: joindin:sync {eventId}
Optional Argument: joindin:sync {eventId?}
Argument with default: joindin:sync {eventId=all}
Boolean Option: joindin:sync --wipeOldEvents
Option with Value: joindin:sync --afterDate=
Option with Value and Default: joindin:sync --afterDate=1999-01-01

Note that you can also add descriptions inline:
protected $signature = 'joindin:sync
{eventId? : (optional) The ID of the event to sync}
{--wipeOldEvents : Whether to replace all locally-stored events with API results}';

$this->anticipate()
$this->argument()
$this->ask()
$this->choice()
$this->confirm()
$this->error()
$this->info()
$this->option()
$this->output->progressAdvance();
$this->output->progressFinish();
$this->output->progressStart($numUnits);
$this->secret()
$this->table()
*/
class PhpInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP Info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line(phpinfo());
    }
}
