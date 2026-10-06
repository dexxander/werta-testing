<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GuidanceCenterTest extends TestCase
{
    public function test_guidance_center_is_available_without_configured_locations(): void
    {
        config()->set('guidance-center.locations', []);

        $this->get('/guidance-center')
            ->assertOk()
            ->assertSee('Guidance Center')
            ->assertDontSee('id="guidance-location-details"', false);
    }

    public function test_guidance_center_displays_multiple_configured_locations(): void
    {
        config()->set('guidance-center.locations', [
            [
                'name' => 'First Center',
                'address' => 'First Address',
                'additional_details' => 'First details',
                'latitude' => 5.9,
                'longitude' => 116.0,
            ],
            [
                'name' => 'Second Center',
                'address' => 'Second Address',
                'additional_details' => 'Second details',
                'latitude' => 6.0,
                'longitude' => 116.1,
            ],
        ]);

        $this->get('/guidance-center')
            ->assertOk()
            ->assertSee('First Center')
            ->assertSee('Second Center')
            ->assertSee('images\/Werta_pin.png', false)
            ->assertSee('id="guidance-location-details"', false)
            ->assertSee('activeLocationIndex !== index', false)
            ->assertSee('detailsPanel.hidden = true', false)
            ->assertSee("markerElement.setAttribute('aria-expanded', 'true')", false);
    }

    public function test_each_location_returns_its_configured_coordinates(): void
    {
        config()->set('guidance-center.locations', [
            [
                'name' => 'First Center',
                'address' => 'First Address',
                'additional_details' => '',
                'latitude' => 5.9,
                'longitude' => 116.0,
            ],
            [
                'name' => 'Second Center',
                'address' => 'Second Address',
                'additional_details' => '',
                'latitude' => 6.0,
                'longitude' => 116.1,
            ],
        ]);

        $this->get('/guidance-center/locations/1')
            ->assertOk()
            ->assertJson([
                'latitude' => 6.0,
                'longitude' => 116.1,
            ]);
    }

    public function test_location_without_coordinates_can_be_geocoded(): void
    {
        Cache::forget('guidance-center.location.'.sha1('Configured Center, Configured Address'));
        config()->set('guidance-center.locations', [[
            'name' => 'Configured Center',
            'address' => 'Configured Address',
            'additional_details' => '',
            'latitude' => null,
            'longitude' => null,
        ]]);
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([
                ['lat' => '5.9804', 'lon' => '116.0735'],
            ]),
        ]);

        $this->get('/guidance-center/locations/0')
            ->assertOk()
            ->assertJson([
                'latitude' => 5.9804,
                'longitude' => 116.0735,
            ]);
    }
}
