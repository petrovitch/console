<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

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
class Parts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parts
                            {--special : Default part number as base}
                            {--masks : Alphanumeric mask}
                            {--patterns : Regular expression patterns}
                            {--base : Update DB with base part numbers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse base part numbers. {--special} {--masks} {--patterns} {--base}';

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
        $masks = [];
        $results = DB::select("SELECT * FROM parts.parts ORDER BY LENGTH(part_number) DESC, part_number");
        foreach ($results as $row) {
            $id = $row->id;
            $field = trim($row->part_number);
            $base = '';
            if (preg_match('/(.*?)/', $field, $match)) {
                $mask = preg_replace('/[0-9]/', '9', $field);
                $mask = preg_replace('/[a-zA-Z]/', 'A', $mask);
                $masks[] = $mask;
            }
        }
        $masks = array_unique($masks);
        $this->line(sizeof($masks));
        sort($masks);
        $str = '';
        $i = 0;
        foreach ($masks as $mask) {
            $str .= $mask . "\n";
        }
        if ($this->option('masks')) {
            file_put_contents('masks.txt', $str);
        }

        $patterns = [];
        for ($i = 0; $i < sizeof($masks); $i++) {
            $pattern = $masks[$i];
            $this->line($pattern);
            $pattern = preg_replace('/[0-9]/', '[0-9]', $pattern);
            $pattern = preg_replace('/[A-Z]/', '[A-Z]', $pattern);
            for ($j = 20; $j > 1; $j--) {
                $pattern = preg_replace('/(\[0-9\]){' . $j . '}/', '[0-9]{' . $j . '}', $pattern);
                $pattern = preg_replace('/(\[A-Z\]){' . $j . '}/', '[A-Z]{' . $j . '}', $pattern);
            }
            $patterns[] = $pattern;
        }
        $str = '';
        $this->line('SQL Patterns');
        for ($i = 0; $i < sizeof($patterns); $i++) {
            $this->line($masks[$i] . ' =>' . $patterns[$i]);
            $str .= $patterns[$i] . "\n";
        }
        if ($this->option('patterns')) {
            file_put_contents('patterns.txt', $str);
        }

        $this->line('');
        $this->info('Patterns');
        $this->line(sizeof($patterns));

        if ($this->option('base')) {
            $results = DB::select("SELECT * FROM parts.parts");
            foreach ($results as $row) {
                $id = $row->id;
                $field = trim($row->part_number);
                $base = '';
                if ($this->option('special')) {
                    $base = $field;
                }
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $field, $match2)) {
                        $base = $match2[1];
                        break;
                    }
                }
                //$this->line($field . ' => ' . $base);
                $results = DB::select("UPDATE parts.parts SET base_part_number = '$base' WHERE id = '$id'");
            }
        }
    }
}
