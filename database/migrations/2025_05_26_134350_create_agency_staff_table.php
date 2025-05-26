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
        Schema::create('agency_staff', function (Blueprint $table) {
            $table->id('StaffID'); // PK
            $table->string('StaffName', 50);
            $table->string('Password', 255);
            $table->string('staffEmail', 50)->unique();
            $table->string('staffPhoneNum', 15);
            $table->string('ProfilePic', 100)->nullable();
            $table->text('LoginHistory')->nullable();
            $table->unsignedBigInteger('AgencyID'); // FK
            $table->timestamps();

            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_staff');
    }
};
