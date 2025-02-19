<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Middleware\HandleInertiaRequests;

final class HandleInertiaNovaLicense extends HandleInertiaRequests
{
    /**
     * @return array<string, string>
     */
    public function share(Request $request): array
    {
        return array_merge((array) parent::share($request), [
            'validLicense' => fn(): bool => true,
        ]);
    }
}
