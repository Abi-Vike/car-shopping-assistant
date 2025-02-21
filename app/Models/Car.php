<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

final class Car extends Model
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
        'embedding',
    ];

    protected $casts = [
        'embedding' => 'array', // Cast the embedding column to an array
        'year' => 'integer',
    ];

    public static function generateEmbedding($text)
    {
        $apiKey = env('GEMINI_API_KEY', 'AIzaSyDi_ciQSTp_U6l40wesaZKinXhb01lZuV4'); // Ensure this is in your .env file

        $response = Http::withOptions(['verify' => false]) // Disable SSL verification
            ->post("https://generativelanguage.googleapis.com/v1beta/models/text-embedding-004:embedContent?key={$apiKey}", [
                'model' => 'models/text-embedding-004',
                'content' => [
                    'parts' => [
                        [
                            'text' => $text, // Explicitly sending text as per your documentation
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['embedding']['values'])) {
                return $data['embedding']['values'];
            }
            if (isset($data['embeddings'][0]['values'])) {
                return $data['embeddings'][0]['values']; // Adjust based on actual response structure
            }
            throw new Exception('Unexpected response structure from Gemini API: '.json_encode($data));
        }

        throw new Exception('Failed to generate embedding: '.$response->body());
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function updateEmbedding()
    {
        $textToEmbed = $this->name.' '.$this->description.' '.$this->make.' '.$this->model.' '.$this->location;
        $this->embedding = self::generateEmbedding($textToEmbed);
        $this->save();
    }
}
