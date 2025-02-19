<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makes = ['Toyota', 'Hyundai', 'Nissan', 'Honda', 'Mitsubishi']; // Common makes in Ethiopia
        $models = ['Corolla', 'Tucson', 'Sunny', 'Civic', 'L200']; // Common models
        $locations = ['Addis Ababa', 'Jimma', 'Dire Dawa', 'Hawassa', 'Mekelle', 'Bahir Dar']; // Ethiopian cities
        $conditions = ['new', 'used', 'refurbished'];
        $transmissions = ['manual', 'automatic'];
        $fuelTypes = ['electric', 'gas', 'diesel']; // electric, gas and diesel

        // Realistic descriptions for testing embeddings
        $descriptions = [
            "A well-maintained " . $this->faker->randomElement($makes) . " " . $this->faker->randomElement($models) . " available in " . $this->faker->randomElement($locations) . ". Features include " . $this->faker->randomElement($transmissions) . " transmission, " . $this->faker->randomElement($fuelTypes) . " engine, and suitable for Ethiopian roads. Low mileage, perfect for families.",
            "Imported " . $this->faker->randomElement($makes) . " " . $this->faker->randomElement($models) . " in excellent condition, located in " . $this->faker->randomElement($locations) . ". " . $this->faker->randomElement($conditions) . ", with " . $this->faker->numberBetween(1000, 150000) . " km mileage, " . $this->faker->randomElement($fuelTypes) . " fuel type, and four-wheel drive for rough terrains.",
            "Affordable " . $this->faker->randomElement($makes) . " " . $this->faker->randomElement($models) . " for sale in " . $this->faker->randomElement($locations) . ". " . $this->faker->randomElement($conditions) . " condition, " . $this->faker->randomElement($transmissions) . " transmission, ideal for city driving and long trips in Ethiopia.",
            "High-quality " . $this->faker->randomElement($makes) . " " . $this->faker->randomElement($models) . " with " . $this->faker->randomElement($fuelTypes) . " engine, located in " . $this->faker->randomElement($locations) . ". New model, low mileage, and equipped for Ethiopian road conditions.",
            "Used but reliable " . $this->faker->randomElement($makes) . " " . $this->faker->randomElement($models) . " in " . $this->faker->randomElement($locations) . ". Features " . $this->faker->randomElement($transmissions) . " transmission, " . $this->faker->randomElement($fuelTypes) . " fuel, and is perfect for commercial use in Ethiopia."
        ];

        return [
            'name' => $this->faker->randomElement($makes) . ' ' . $this->faker->randomElement($models),
            'description' => $this->faker->randomElement($descriptions), // Use realistic descriptions
            'images' => json_encode([$this->faker->imageUrl(640, 480, 'vehicles', true)]), // Fake image URL
            'price' => $this->faker->numberBetween(300000, 5000000), // Prices in Ethiopian Birr (ETB)
            'fuel_type' => $this->faker->randomElement($fuelTypes),
            'seating_capacity' => $this->faker->randomElement([5, 7, 8]),
            'make' => $this->faker->randomElement($makes),
            'model' => $this->faker->randomElement($models),
            'year' => $this->faker->year(), // Random year
            'is_imported' => $this->faker->boolean(90), // 90% chance it's imported (common in Ethiopia)
            'condition' => $this->faker->randomElement($conditions),
            'transmission' => $this->faker->randomElement($transmissions),
            'location' => $this->faker->randomElement($locations),
            'four_wheel_drive' => $this->faker->boolean(30), // 30% chance for 4WD (useful for Ethiopian terrain)
            'mileage' => $this->faker->optional()->numberBetween(1000, 150000), // Kilometers
            'owner_id' => User::factory(['role' => 'seller'])->create()->id, // Assume a user exists or create one
            'embedding' => null, // Embeddings will be generated later via the model
            'created_at' => fake()->dateTimeBetween('-12 months', '-1 days'),
        ];
    }
}
