<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-storage-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from public/storage to storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating storage link...');
        
        Artisan::call('storage:link');
        $this->info(Artisan::output());
        
        $this->info('Storage link created successfully.');
        
        return Command::SUCCESS;
    }
}
