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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('address1')->nullable();
        $table->string('address2')->nullable();
        $table->string('city')->nullable();
        $table->string('county')->nullable();
        $table->string('postcode')->nullable();
        $table->string('type')->nullable(); // collection or delivery
        $table->string('payment_method')->nullable(); // cash or card
        $table->timestamp('schedule')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
