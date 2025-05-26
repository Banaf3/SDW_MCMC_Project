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
        Schema::create('administrators', function (Blueprint $table) {
            $table->id('AdminID'); // PK
            $table->string('AdminName', 50);
            $table->string('Password', 255);
            $table->string('AdminEmail', 50)->unique();
            $table->string('AdminRole', 30);
            $table->string('AdminPhoneNum', 15);
            $table->string('AdminAddress', 100);
            $table->text('LoginHistory')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrators');
    }
};
