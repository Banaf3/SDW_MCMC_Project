<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('administrators', function (Blueprint $table) {
            if (!Schema::hasColumn('administrators', 'ProfilePicture')) {
                $table->string('ProfilePicture', 100)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrators', function (Blueprint $table) {
            if (Schema::hasColumn('administrators', 'ProfilePicture')) {
                $table->dropColumn('ProfilePicture');
            }
        });
    }
};
