<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefactorModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:refactor-models
                            {--fill : Fillables}
                            {--cast : Casts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace fillable and cast variables in each model. {--fill} {--cast}';

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
         * Models, by default in Laravel 5.1, are located in the app folder.
         * In this project a folder called Models has been created under app.
         * This is defined in the .env file.
         *
         * MODELS=app/Models/
         */
        $pathModels = env('MODELS');

        /**
         * php wordwrap width
         */
        $width = 120;

        // Path to database migration files
        $path = "database/migrations/";
        $files = scandir($path);
        // Get file names excluding directories and dots
        for ($i = 0; $i < sizeof($files); $i++) {
            if (preg_match('/^[.]/', $files[$i], $match)) {
                unset($files[$i]);
            }
        }
        #print_r($files);

        // Fill
        if ($this->option('fill')) {
            $this->info('Fill ...');
            $models = array();
            foreach ($files as $file) {
                if (file_exists($path . $file)) {
                    // Get contents of this database migration file
                    $contents = file_get_contents($path . $file);
                    #echo $contents;
                    // Get database table name
                    if (preg_match('/Schema::.*?\{(.*?)\}\)\;/is', $contents, $match)) {
                        $str = $match[1];
                    }
                    #echo $str;
                    // Get all column names from table
                    if (preg_match_all('/table.*?\([\'](.*?)[\']/', $str, $matches)) {
                        $s = "protected \$fillable = [\n";
                        for ($i = 0; $i < sizeof($matches[1]); $i++) {
                            $s .= "\t\t'" . $matches[1][$i] . "',\n";
                        }
                        $s .= "\t];";
                        $s = preg_replace('/\'id\', /', '', $s);
                        $s = wordwrap($s, $width);
                        #$this->line($s);
                    }
                    $model = preg_replace('/^.*?create_(.*?)_table.*/', "$1", $file);
                    $model = str_singular($model);
                    #$this->line($model);
                    $models[] = $model;

                    $fileName = $pathModels . ucfirst($model . '.php');
                    #echo $fileName;
                    if (file_exists($fileName)) {
                        #$this->info('file exists');
                        // Get contents of model file
                        $text = file_get_contents($fileName);
                        #$this->line($contents);
                        // Replace fillable section with new string
                        if (preg_match('/(protected \$fillable.*?;)/s', $text, $match)) {
                            $text = preg_replace('/(protected \$fillable.*?;)/s', $s, $text);
                        }
                        file_put_contents($fileName, $text);
                        #$this->line($text);
                        #$this->line($model);
                    }
                }
            }
        }

        // Cast
        if ($this->option('cast')) {
            $this->info('Cast ...');
            // Unset these data types
            $unset = array('increments', 'date', 'datetime', 'enum', 'string', 'text', 'timestamp');
            // Loop through each data migrations file
            foreach ($files as $file) {
                if (file_exists($path . $file)) {
                    // Get model name
                    $model = preg_replace('/^.*?create_(.*?)_table.*/', "$1", $file);
                    // Singular
                    $model = preg_replace('/ies$/', "y", $model);
                    $model = preg_replace('/s$/', "", $model);
                    $types = array();
                    $vars = array();
                    // Get contents of database migration file
                    $contents = file_get_contents($path . $file);
                    // Get substring containing variable type and variable name
                    if (preg_match_all("/table->(.*?)\'\)/", $contents, $match)) {
                        for ($i = 0; $i < sizeof($match[1]); $i++) {
                            $s = $match[1][$i];
                            // Get variable type and variable name
                            if (preg_match('/(.*?)\(\'(.*)/', $s, $match2)) {
                                if (!in_array($match2[1], $unset)) {
                                    $types[] = $match2[1];
                                    $vars[] = $match2[2];
                                }
                            }
                        }
                    }
                    // Build string for casting each variable name and type
                    $s = '';
                    for ($i = 0; $i < sizeof($types); $i++) {
                        $s .= "\t\t'" . $vars[$i] . "' => '" . $types[$i] . "',\n";
                    }
                    // Get model file name
                    $fileName = $pathModels . $model . ".php";
                    if (file_exists($fileName)) {
                        $text = file_get_contents($fileName);
                        // Create casting list
                        if (preg_match('/protected \$casts = \[(.*?)\];/is', $text)) {
                            if (strlen($s) > 0) {
                                $text = preg_replace('/protected \$casts = \[(.*?)\];/is', "protected \$casts = [\n$s\t];", $text);
                            }
                            file_put_contents($fileName, $text);
                            #$this->line($text);
                            #$this->line($model);
                        }
                    }
                }
            }
        }
    }
}
