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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('InquiryID'); // PK
            $table->string('InquiryTitle', 100);
            $table->date('SubmitionDate');
            $table->string('InquiryStatus', 50);
            $table->text('StatusHistory')->nullable();
            $table->text('InquiryDescription');
            $table->string('InquiryEvidence', 255)->nullable();
            $table->text('AdminComment')->nullable();
            $table->text('ResolvedExplanation')->nullable();
            $table->string('ResolvedSupportingDocs', 255)->nullable();
            $table->text('AgencyRejectionComment')->nullable();

            $table->unsignedBigInteger('AgencyID');
            $table->unsignedBigInteger('AdminID');
            $table->unsignedBigInteger('UserID');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
            $table->foreign('AdminID')->references('AdminID')->on('administrators')->onDelete('cascade');
            $table->foreign('UserID')->references('UserID')->on('public_users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
