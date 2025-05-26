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
        Schema::create('assigned_inquiries', function (Blueprint $table) {
            $table->unsignedBigInteger('AdminID');
            $table->unsignedBigInteger('AgencyID');
            $table->unsignedBigInteger('InquiryID');
            $table->date('AssignedDate');

            // Composite Primary Key
            $table->primary(['AdminID', 'AgencyID', 'InquiryID']);

            // Foreign Keys
            $table->foreign('AdminID')->references('AdminID')->on('administrators')->onDelete('cascade');
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
            $table->foreign('InquiryID')->references('InquiryID')->on('inquiries')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_inquiries');
    }
};
