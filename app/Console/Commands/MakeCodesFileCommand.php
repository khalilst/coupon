<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCodesFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:codes {filename} {--count=500000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the codes command';

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
        $path = storage_path('codes/' . $this->argument('filename'));
        $count = $this->option('count');

        $bar = $this->output->createProgressBar($count);
        $this->info("\nCreating codes file with $count lines...\n");
        $bar->start();

        $file = fopen($path, 'w');

        for ($i=0; $i < $count; $i++) {
            fwrite($file, randomStr() . PHP_EOL);
            $bar->advance();
        }

        fclose($file);
        $this->info("\nCodes file created: $path\n");
    }
}
