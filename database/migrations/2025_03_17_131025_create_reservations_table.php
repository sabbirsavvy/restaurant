<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable(); // If you want to link reservations to a registered user
        $table->string('name');                           // Customer's name
        $table->date('date');                             // Reservation date
        $table->time('time');                             // Reservation time
        $table->integer('number_of_guests');              // Number of guests
        $table->text('special_requests')->nullable();     // Optional requests
        $table->string('status')->default('pending');     // E.g., pending, confirmed, rejected
        $table->timestamps();

        // If linking to users table:
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
