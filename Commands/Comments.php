<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Comments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:remove-comments
                            {file : (required) File from which you wish to remove comments}
                            {--double-slash : Remove double slashed comments //}
                            {--single : Remove single line comments, /* */}
                            {--block : Remove multi-line comments /** */}
                            {--purge : Remove multiple blank lines (allow singles)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove comments by type from selected file.';

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
        // Is there an argument for file name
        $file = $this->argument('file');
        // If no argument is given then ask user for file name
        if (strlen($file) == 0){
            $file = $this->ask('File');
        }
        // If file name given (via command line or prompt) then open file
        if (file_exists($file)) {
            // Get contents of file
            $contents = file_get_contents($file);
            //$this->line($contents);

            // Init boolean flag
            $changes = false;

            // Double-slash comments
            if ($this->option('double-slash')) {
                // Split string into array of lines
                $lines = preg_split('/\n/', $contents);
                for ($i = 0; $i < sizeof($lines); $i++) {
                    if (preg_match('/\/\/.*/', $lines[$i], $match)) {
                        unset($lines[$i]);
                        $changes = true;
                    }
                }
                // Convert array of lines into a string
                $contents = implode("\n", $lines);
            }

            /* Single line comments */
            if ($this->option('single')) {
                if (preg_match('/\/\*[^*].*?\*\//s', $contents, $match)) {
                    $contents = preg_replace('/\/\*[^*].*?\*\/\s+/s', '', $contents);
                    $changes = true;
                }
            }

            /**
             * Block multi-line comments
             */
            if ($this->option('block')) {
                if (preg_match('/\/\*\*.*?\*\//s', $contents, $match)) {
                    $contents = preg_replace('/\/\*\*.*?\*\/\s+/s', '', $contents);
                    $changes = true;
                }
            }

            /**
             * Remove multiple blank lines
             */
            if ($this->option('purge')) {
                while (preg_match("/\n\n\n/s", $contents, $match)) {
                    $contents = preg_replace("/(\n\n\n)+/s", "\n\n", $contents);
                    $changes = true;
                }
            }

            // Rewrite file if any comments removed
            if ($changes){
                file_put_contents($file, $contents);
            }
        } else {
            $this->error('No file name given.');
        }
        // Show results
        //$this->line($contents);
    }
}
