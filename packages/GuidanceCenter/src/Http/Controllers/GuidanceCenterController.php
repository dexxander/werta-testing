<?php

namespace GuidanceCenter\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Throwable;

class GuidanceCenterController extends Controller
{
    public function index(): View
    {
        $map = config('guidance-center.map', []);
        $defaultCenter = $map['default_center'] ?? [];
        $locations = $this->configuredLocations();

        foreach ($locations as $index => &$location) {
            $location['coordinateUrl'] = route('guidance-center.location', ['index' => $index]);
        }
        unset($location);

        return view('guidance-center::index', [
            'locations' => $locations,
            'map' => [
                'styleUrl' => (string) ($map['style_url'] ?? 'https://tiles.openfreemap.org/styles/liberty'),
                'defaultLatitude' => (float) ($defaultCenter['latitude'] ?? 4.2105),
                'defaultLongitude' => (float) ($defaultCenter['longitude'] ?? 101.9758),
            ],
        ]);
    }

    public function location(int $index): JsonResponse
    {
        $location = $this->configuredLocations()[$index] ?? null;

        if ($location === null) {
            return response()->json(['message' => 'Location not found.'], 404);
        }

        if ($this->hasValidCoordinates($location)) {
            return response()->json([
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
            ]);
        }

        $query = implode(', ', array_filter([$location['name'], $location['address']]));

        try {
            $coordinates = Cache::remember(
                'guidance-center.location.'.sha1($query),
                now()->addDays(30),
                function () use ($query): ?array {
                    $response = Http::acceptJson()
                        ->withHeaders([
                            'User-Agent' => config('app.name', 'Werta').'/1.0 ('.config('app.url').')',
                            'Accept-Language' => app()->getLocale(),
                        ])
                        ->timeout(8)
                        ->get(config('guidance-center.map.geocoding_url'), [
                            'q' => $query,
                            'format' => 'jsonv2',
                            'limit' => 1,
                        ])
                        ->throw()
                        ->json();

                    if (! isset($response[0]['lat'], $response[0]['lon'])) {
                        return null;
                    }

                    return [
                        'latitude' => (float) $response[0]['lat'],
                        'longitude' => (float) $response[0]['lon'],
                    ];
                }
            );
        } catch (Throwable) {
            $coordinates = null;
        }

        if ($coordinates === null) {
            return response()->json(['message' => 'The configured address could not be located.'], 404);
        }

        return response()->json($coordinates);
    }

    private function configuredLocations(): array
    {
        return collect(config('guidance-center.locations', []))
            ->map(function ($location): array {
                $location = is_array($location) ? $location : [];

                return [
                    'name' => trim((string) ($location['name'] ?? '')),
                    'address' => trim((string) ($location['address'] ?? '')),
                    'additionalDetails' => trim((string) ($location['additional_details'] ?? '')),
                    'latitude' => is_numeric($location['latitude'] ?? null) ? (float) $location['latitude'] : null,
                    'longitude' => is_numeric($location['longitude'] ?? null) ? (float) $location['longitude'] : null,
                ];
            })
            ->filter(fn (array $location): bool => $location['address'] !== '')
            ->values()
            ->all();
    }

    private function hasValidCoordinates(array $location): bool
    {
        if (! is_numeric($location['latitude']) || ! is_numeric($location['longitude'])) {
            return false;
        }

        return $location['latitude'] >= -90 && $location['latitude'] <= 90
            && $location['longitude'] >= -180 && $location['longitude'] <= 180;
    }
}
