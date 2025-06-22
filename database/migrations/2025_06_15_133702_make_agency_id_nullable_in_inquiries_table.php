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
            // Drop existing foreign key constraints first
            $table->dropForeign(['AgencyID']);
            $table->dropForeign(['AdminID']);
            
            // Modify columns to be nullable
            $table->unsignedBigInteger('AgencyID')->nullable()->change();
            $table->unsignedBigInteger('AdminID')->nullable()->change();
            
            // Re-add foreign key constraints with onDelete('set null')
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('set null');
            $table->foreign('AdminID')->references('AdminID')->on('administrators')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['AgencyID']);
            $table->dropForeign(['AdminID']);
            
            // Change columns back to not nullable
            $table->unsignedBigInteger('AgencyID')->nullable(false)->change();
            $table->unsignedBigInteger('AdminID')->nullable(false)->change();
            
            // Re-add foreign key constraints with onDelete('cascade')
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
            $table->foreign('AdminID')->references('AdminID')->on('administrators')->onDelete('cascade');
        });
    }
};
