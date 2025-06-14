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
            if (!Schema::hasColumn('administrators', 'Username')) {
                $table->string('Username', 50)->nullable()->unique()->after('AdminName');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrators', function (Blueprint $table) {
            if (Schema::hasColumn('administrators', 'Username')) {
                $table->dropColumn('Username');
            }
        });
    }
};