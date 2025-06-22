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
            $table->string('Username', 50)->unique(); // Required for login
            $table->string('Password', 255);
            $table->boolean('password_change_required')->default(true); // Force password change
            $table->timestamp('password_changed_at')->nullable(); // Track password changes
            $table->string('staffEmail', 50)->nullable(); // Made optional
            $table->string('staffPhoneNum', 15);
            $table->string('ProfilePic', 100)->nullable();
            $table->text('LoginHistory')->nullable();
            $table->unsignedBigInteger('AgencyID'); // FK
            $table->timestamps();

            $table->foreign('AgencyID')->references('AgencyID')->on('agencies')->onDelete('cascade');
            
            // Ensure email is unique when provided
            $table->unique('staffEmail');
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
