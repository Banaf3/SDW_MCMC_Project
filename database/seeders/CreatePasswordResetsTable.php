<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class CreatePasswordResetsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
            
            $this->command->info('Created password_resets table.');
        } else {
            $this->command->info('password_resets table already exists.');
        }
    }
}
