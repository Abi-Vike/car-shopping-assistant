<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'images' => 'array',
            'price' => 'required|numeric',
            'fuel_type' => 'required|in:electric,gas,diesel',
            'seating_capacity' => 'required|integer',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'is_imported' => 'boolean',
            'condition' => 'required|in:new,used,refurbished',
            'transmission' => 'required|in:manual,automatic',
            'location' => 'required|string|in:Addis Ababa,Dire Dawa,Hawassa,Mekelle,Bahir Dar', // Ethiopian cities
            'four_wheel_drive' => 'boolean',
            'mileage' => 'nullable|integer',
            'owner_id' => 'required|exists:users,id',
        ]);

        $car = Car::create($data);
        $car->updateEmbedding();

        return response()->json(['message' => 'Car added successfully', 'car' => $car], 201);
    }

    public function search(Request $request)
    {
        $query = $request->input('query', '');

        // Generate embedding for the user's query
        $queryEmbedding = Car::generateEmbedding($query);

        // Fetch all cars with their embeddings
        $cars = Car::all();

        // Calculate similarity
        $similarCars = $cars->map(function ($car) use ($queryEmbedding) {
            if (!$car->embedding || !is_array($car->embedding)) {
                return null; // Skip cars without valid embeddings
            }

            $similarity = $this->cosineSimilarity($queryEmbedding, $car->embedding);
            return ['car' => $car, 'similarity' => $similarity];
        })->filter()->sortByDesc('similarity')->take(10)->values();

        // Filter by Ethiopia-specific criteria if provided
        if ($request->filled('location') && $request->input('location') !== '') {
            $similarCars = $similarCars->filter(function ($item) use ($request) {
                return strtolower($item['car']->location) === strtolower($request->input('location'));
            });
        }

        if ($request->filled('fuel_type') && $request->input('fuel_type') !== '') {
            $similarCars = $similarCars->filter(function ($item) use ($request) {
                return strtolower($item['car']->fuel_type) === strtolower($request->input('fuel_type'));
            });
        }

        return response()->json(['cars' => $similarCars->pluck('car')]);
    }

    private function cosineSimilarity($vec1, $vec2)
    {
        // Convert to arrays if they are JSON strings or not arrays
        $vec1 = is_string($vec1) ? json_decode($vec1, true) : (array) $vec1;
        $vec2 = is_string($vec2) ? json_decode($vec2, true) : (array) $vec2;

        // Ensure both are arrays
        if (!is_array($vec1) || !is_array($vec2)) {
            throw new \Exception('Invalid embedding format: vectors must be arrays');
        }

        // Pad or truncate vectors to ensure they have the same length
        $maxLength = max(count($vec1), count($vec2));
        $vec1 = array_pad($vec1, $maxLength, 0);
        $vec2 = array_pad($vec2, $maxLength, 0);

        $dotProduct = array_sum(array_map(function ($a, $b) {
            return $a * $b;
        }, $vec1, $vec2));

        $magnitude1 = sqrt(array_sum(array_map(function ($x) {
            return $x * $x;
        }, $vec1)));

        $magnitude2 = sqrt(array_sum(array_map(function ($x) {
            return $x * $x;
        }, $vec2)));

        if ($magnitude1 * $magnitude2 == 0) return 0;

        return $dotProduct / ($magnitude1 * $magnitude2);
    }
}
