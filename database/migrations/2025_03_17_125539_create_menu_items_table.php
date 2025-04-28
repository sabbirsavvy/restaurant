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
    Schema::create('menu_items', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category')->nullable(); // Added category column
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2);
        $table->string('image')->nullable();
        $table->boolean('is_featured')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
