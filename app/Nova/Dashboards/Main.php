<?php

declare(strict_types=1);

namespace App\Nova\Dashboards;

use App\Nova\Metrics\AverageCarPrice;
use Laravel\Nova\Dashboards\Main as Dashboard;

final class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            (new AverageCarPrice())->width('1/2'),
            (new \App\Nova\Metrics\CarsByFuelType())->width('1/2'),
            (new \App\Nova\Metrics\CarsByLocation())->width('1/2'),
            (new \App\Nova\Metrics\TotalCars())->width('1/2'),
            (new \App\Nova\Metrics\UsersByRole())->width('1/3'),
            (new \App\Nova\Metrics\UsersPerDay())->width('2/3'),
        ];
    }
}
