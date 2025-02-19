<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'images',
        'price',
        'fuel_type',
        'seating_capacity',
        'make',
        'model',
        'year',
        'is_imported',
        'condition',
        'transmission',
        'location',
        'four_wheel_drive',
        'mileage',
        'owner_id',
        'embedding'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public static function generateEmbedding($text)
    {
        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
        ])->post('https://api.gemini.ai/v1/embeddings', [
            'input' => $text,
            'model' => 'text-embedding-004',
        ]);

        if ($response->successful()) {
            return $response->json()['embedding'];
        }

        throw new \Exception('Failed to generate embedding: ' . $response->body());
    }

    public function updateEmbedding()
    {
        $textToEmbed = $this->name . ' ' . $this->description . ' ' . $this->make . ' ' . $this->model . ' ' . $this->location;
        $this->embedding = self::generateEmbedding($textToEmbed);
        $this->save();
    }
}
