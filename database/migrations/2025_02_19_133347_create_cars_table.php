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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Toyota Corolla"
            $table->text('description')->nullable();
            $table->text('images')->nullable(); // Store array of image URLs or paths
            $table->decimal('price', 8, 2); // Price in Ethiopian Birr (ETB)
            $table->enum('fuel_type', ['electric', 'gas', 'diesel'])->default('gas'); // Added diesel for Ethiopian market
            $table->integer('seating_capacity'); // e.g., 5, 7, 8
            $table->string('make'); // Manufacturer, e.g., Toyota, Hyundai
            $table->string('model'); // Model name, e.g., Camry
            $table->year('year'); // Manufacturing year
            $table->boolean('is_imported')->default(true); // Whether the car is imported (common in Ethiopia)
            $table->string('condition')->default('used'); // e.g., new, used, refurbished
            $table->string('transmission')->default('manual'); // e.g., manual, automatic (manual is common in Ethiopia)
            $table->string('location')->nullable(); // City or region in Ethiopia, e.g., Addis Ababa
            $table->boolean('four_wheel_drive')->default(false); // Important for Ethiopian terrain
            $table->integer('mileage')->nullable(); // Kilometers or miles (common metric in Ethiopia is km)
            $table->json('embedding')->nullable(); // Store Gemini embedding as JSON
            $table->unsignedBigInteger('owner_id'); // Foreign key to users (car owners/sellers)
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
