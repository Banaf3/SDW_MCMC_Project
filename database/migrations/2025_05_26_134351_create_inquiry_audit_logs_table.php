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
        Schema::create('inquiry_audit_logs', function (Blueprint $table) {
            $table->id('AuditLogID'); // PK
            $table->string('Action', 255);
            $table->string('PerformedBy', 100);
            $table->timestamp('ActionDate');
            $table->unsignedBigInteger('InquiryID'); // FK
            $table->timestamps();

            $table->foreign('InquiryID')->references('InquiryID')->on('inquiries')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_audit_logs');
    }
};
