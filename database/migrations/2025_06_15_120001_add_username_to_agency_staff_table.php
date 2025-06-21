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
        Schema::table('agency_staff', function (Blueprint $table) {
            if (!Schema::hasColumn('agency_staff', 'Username')) {
                $table->string('Username', 50)->nullable()->unique()->after('StaffName');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency_staff', function (Blueprint $table) {
            if (Schema::hasColumn('agency_staff', 'Username')) {
                $table->dropColumn('Username');
            }
        });
    }
};