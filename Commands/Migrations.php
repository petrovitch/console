<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Migrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:show-migrations
                            {--write : Output migrations to text file (alternative bash > output)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all migration schemas.';

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
        // Path to database migration files
        $path = "database/migrations/";
        // Scan directory for database migration files
        $files = scandir($path);
        // Get only migration files excluding directories and dots
        for ($i = 0; $i < sizeof($files); $i++){
            if (preg_match('/^[.]/', $files[$i], $match)){
                unset($files[$i]);
            }
        }
        // Sort files to order functions on output
        sort($files);

        $s = '';
        foreach ($files as $file){
            $contents = file_get_contents($path . $file);
            // Fetch the schema from file contents
            if (preg_match('/(Schema::.*?\}\)\;)/is', $contents, $match)){
                $s .= $match[1];
            }
            $s .=  chr(10) . chr(10);
        }
        // Show each line of schema (this is not a diagnostic line out)
        $this->line($s);

        // Output to file?
        if ($this->option('write')){
            $output = 'artisan.migrations.txt';
            file_put_contents($output, $s);
            $this->info('Output');
            $this->line($output);
        }
    }
}
