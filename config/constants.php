<?php

return [
    'property_sub_type' => [
        'residential' => [
            'flat' => 'Flat',
            'house' => 'House',
            'bungalow' => 'Bungalow',
            'villa' => 'Villa',
            'land' => 'Land',
            'plot' => 'Plot',
        ],
        'commercial' => [
            'office' => 'Office',
            'shops' => 'Shops',
            'showroom' => 'Showroom',
            'land' => 'Land',
            'plot' => 'Plot',
        ],
    ],
    'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY', 'AIzaSyCzW1Vua7cCoE8_36mnMpRbaya5TZ0Q13A'),
];
