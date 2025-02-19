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
        $locations = ['Addis Ababa', 'Dire Dawa', 'Hawassa', 'Mekelle', 'Bahir Dar']; // Ethiopian cities
        $conditions = ['new', 'used', 'refurbished'];
        $transmissions = ['manual', 'automatic'];
        $fuelTypes = ['gas', 'diesel']; // Electric is rare in Ethiopia, so we focus on gas and diesel

        return [
            'name' => $this->faker->randomElement($makes) . ' ' . $this->faker->randomElement($models),
            'description' => $this->faker->paragraph(),
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
        ];
    }
}
