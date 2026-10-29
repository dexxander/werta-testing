<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guidance Center locations
    |--------------------------------------------------------------------------
    |
    | Developers: add each visitor-facing location to this list.
    | Entries with an empty "address" are not shown.
    | Coordinates are optional precise overrides. When omitted, the configured
    | address is geocoded and cached automatically to position the map pin.
    |
    | Example of adding a new location:
    |
    | [
    | 'name' => 'New Center',
    | 'address' => 'Full address',
    | 'additional_details' => 'Optional details',
    | 'latitude' => 5.123456,
    | 'longitude' => 116.123456,
    | ],
    |
    |TODO : Update the location for pin here
    */


    'locations' => [
        [
        'name' => 'KK High School',
        'address' => 'Simpang Jalan Bunga Raja Kayu , Off Jalan Penampan, Kota Kinabalu, 88300 Kota Kinabalu, Sabah',
        'additional_details' => '',
        'latitude' => 5.9465602326633356,
        'longitude' => 116.07144517128678,
    ],
    ],

    'map' => [
        'style_url' => 'https://tiles.openfreemap.org/styles/liberty',
        'geocoding_url' => 'https://nominatim.openstreetmap.org/search',
        'default_center' => [
            'latitude' => 4.2105,
            'longitude' => 101.9758,
        ],
    ],
];
