<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Model extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zulu:make-model {model} {--c} {--m} {--s}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make custom model with options {--c} {--m} {--s}.';

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
        $modelPath = env('MODELS');

        $s = file_get_contents('app/Console/Commands/stubs/Model.stub');
        $s = $this->replace($s, $model);
        $output = $modelPath . str_singular($model) . '.php';
        file_put_contents($output, $s);

        // {-c} Controller
        if ($this->option('c')) {
            $s = file_get_contents('app/Console/Commands/stubs/Controller.stub');
            $s = $this->replace($s, $model);
            $output = "app/Http/Controllers/" . str_plural($model) . 'Controller.php';
            file_put_contents($output, $s);
            $this->line($s);
        }

        // {-m} Migration
        if ($this->option('m')){
            $s = file_get_contents('app/Console/Commands/stubs/Schema.stub');
            $s = $this->replace($s, $model);
            $output = "database/migrations/" . date('Y_m_d_his') . "_create_" . str_plural($model) . 'table.php';
            file_put_contents($output, $s);
            $this->line($s);
        }

        // {-s} Seeder
        if ($this->option('s')){
            $s = file_get_contents('app/Console/Commands/stubs/Seeder.stub');
            $s = $this->replace($s, $model);
            $output = "database/seeds/" . str_plural($model) . 'TableSeeder.php';
            file_put_contents($output, $s);
        }
    }
}
