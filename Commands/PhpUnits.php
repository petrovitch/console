<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class PhpUnits
 * @package App\Console\Commands
 * @deprecated (under construction)
 */
class PhpUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:phpunit {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make basic phpunit tests.';

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
        /**
         * UNDER CONSTRUCTION
         */

        // phpunit file
//        $path = "tests/";
//        $file = $this->argument('file');
//        $contents = file_get_contents($path . $file);

        $history = array();
        $home = '/';
        $html = file_get_contents("http://www.echo.local/");

        //<a class="font-12" href="#">Register</a>
        if (preg_match_all('/href=[\'\"](.*?)[\'\"].*?>(.*?)</is', $html, $match)) {
            for ($i = 0; $i < sizeof($match[1]); $i++) {
                $link = trim($match[1][$i]);
                $label = trim($match[2][$i]);
                if (strlen($label) > 0) {
                    $name = preg_replace('/\s+/', '', ucwords(strtolower($label)));
                    if (!in_array($name, $history)) {
                        $history[] = $name;
                        $s = file_get_contents('app/Console/Commands/Stubs/PhpUnit.stub');
                        $s = preg_replace('/{{name}}/', $name, $s);
                        $s = preg_replace('/{{home}}/', $home, $s);
                        $s = preg_replace('/{{label}}/', $label, $s);
                        $s = preg_replace('/{{link}}/', $link, $s);
                        $this->line($s);
                    }
                }
            }
        }
    }
}
