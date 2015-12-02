<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class Seeder
 * @package App\Console\Commands
 * @deprecated replaced by Model.php
 */
class Seeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:make-seeder
                            {model : (required) Model name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make seeder.';

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
     * @param $s
     * @param $model
     * @return mixed
     */
    protected function replace($s, $model)
    {
        $usePath = preg_replace('~/~', '\\', ucwords(env('MODELS')));
        $s = preg_replace('/{{use_path}}/', $usePath, $s);
        $s = preg_replace('/{{model_singular}}/', str_singular($model), $s);
        $s = preg_replace('/{{model_plural_lowercase}}/', strtolower(str_plural($model)), $s);
        $s = preg_replace('/{{model_plural}}/', str_plural($model), $s);
        return $s;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Models path defined in .env
        $model = $this->argument('model');

        $s = file_get_contents('app/Console/Commands/stubs/Seeder.stub');
        $s = $this->replace($s, $model);
        $output = "database/seeds/" . str_plural($model) . 'TableSeeder.php';
        file_put_contents($output, $s);
    }
}
