<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class Versions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Laravel/PHP versions.';

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
     * Show current version of PHP/Laravel
     */
    public function versions()
    {
        // Laravel
        $laravel = app();
        $version = $laravel::VERSION;
        $this->info('Laravel Version ' . $version);

        // PHP
        $this->info('PHP Version ' . PHP_VERSION);

        // MySQL
        $mysql = DB::select("SHOW VARIABLES LIKE '%version%'");
        $this->info('MySQL');
        for ($i = 0; $i < sizeof($mysql); $i++) {
            foreach ($mysql[$i] as $key => $val) {
                $this->line($val);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Software Versions
        $this->versions();
    }
}
