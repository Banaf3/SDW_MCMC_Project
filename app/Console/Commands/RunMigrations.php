<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all migrations and seed the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migrations...');
        
        // Run the migrations
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());
        
        // Seed the database
        $this->info('Seeding the database...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info(Artisan::output());
        
        $this->info('All migrations and seeds have been run successfully.');
        
        return Command::SUCCESS;
    }
}
