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
        Schema::create('public_users', function (Blueprint $table) {
            $table->id('UserID'); // PK
            $table->string('UserName', 50);
            $table->string('UserPhoneNum', 15);
            $table->string('Useraddress', 100);
            $table->string('ProfilePic', 100)->nullable();
            $table->string('UserEmail', 50)->unique();
            $table->string('Password', 255);
            $table->text('LoginHistory')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_users');
    }
};
