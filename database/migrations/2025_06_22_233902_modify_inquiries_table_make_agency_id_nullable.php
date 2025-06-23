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
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['AgencyID']);
            
            // Modify the column to be nullable
            $table->unsignedBigInteger('AgencyID')->nullable()->change();
            
            // Re-add the foreign key constraint with SET NULL on delete
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['AgencyID']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('AgencyID')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
        });
    }
};
