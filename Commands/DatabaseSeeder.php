<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseSeeder extends Command
{
    /**
     * The name and signature of the console command.
     * Zulu is used to group custom commands.
     *
     * @var string
     */
    protected $signature = 'zulu:refactor-database-seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database seeder.';

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
        // Path to ModelTableSeeder that extends seeder
        $pathSeeds = "database/seeds/";
        // Get all table seeders filenames in path
        $arr = scandir($pathSeeds);
        // Get array of only table seeders filenames exclusing directories and dots
        for ($i = 0; $i < sizeof($arr); $i++){
            if (preg_match('/TableSeeder.php/', $arr[$i], $match)){
                $files[] = preg_replace('/.php$/', '', $arr[$i]);
            }
        }
        // Sort files to order table seeder output in DatabaseSeder.php
        sort($files);

        // DatabaseSeeder.php script that serves as a kernel for seeders
        $fileName = "database/seeds/DatabaseSeeder.php";
        // Get contents of file
        $contents = file_get_contents($fileName);
        $s = "\n\t\$seeders = [\n";
        foreach ($files as $file){
            $s .= "\t\t'" . $file . "',\n";
        }
        $s .= "\t];\n\n\tforeach(\$seeders as \$seeder){\n\t\t\$this->call(\$seeder);\n\t}\n";
        // Replace existing calls with string
        $contents = preg_replace('/(Model\:\:unguard\(\)\;)(.*?)(Model\:\:reguard\(\)\;)/is', "$1\n$s\n\t$3", $contents);
        file_put_contents($fileName, $contents);
    }
}
