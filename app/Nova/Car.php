<?php

namespace App\Nova;

use App\Nova\Metrics\CarsByFuelType;
use App\Nova\Metrics\CarsByLocation;
use App\Nova\Metrics\TotalCars;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class Car extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Car>
     */
    public static $model = \App\Models\Car::class;

    public static $policy = Policies\CarPolicy::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'description',
        'make',
        'model',
        'year',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Textarea::make('Description'),
            Image::make('Images')->disk('public')->path('cars'),
            Currency::make('Price', 'price')->currency('ETB')->sortable(), // Ethiopian Birr
            Select::make('Fuel Type', 'fuel_type')->options(['electric' => 'Electric', 'gas' => 'Gas', 'diesel' => 'Diesel']),
            Number::make('Seating Capacity', 'seating_capacity'),
            Text::make('Make'),
            Text::make('Model'),
            Number::make('Year', 'year'),
            Boolean::make('Is Imported', 'is_imported'),
            Select::make('Condition')->options(['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished']),
            Select::make('Transmission')->options(['manual' => 'Manual', 'automatic' => 'Automatic']),
            Select::make('Location')->options([
                'Addis Ababa' => 'Addis Ababa',
                'Jimma' => 'Jimma',
                'Dire Dawa' => 'Dire Dawa',
                'Hawassa' => 'Hawassa',
                'Mekelle' => 'Mekelle',
                'Bahir Dar' => 'Bahir Dar',
            ]),
            Boolean::make('Four Wheel Drive', 'four_wheel_drive'),
            Number::make('Mileage (km)', 'mileage'),
            BelongsTo::make('Owner', 'owner', User::class),
            Textarea::make('Embedding', 'embedding')->onlyOnDetail(), // View only
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new TotalCars(),
            new CarsByLocation(),
            new CarsByFuelType(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
