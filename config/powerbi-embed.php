<?php

return [
    'client_id' => env('POWERBI_CLIENT_ID'),
    'client_secret' => env('POWERBI_CLIENT_SECRET'),
    'tenant_id' => env('POWERBI_TENANT_ID'),
    'test_mode' => env('POWERBI_TEST_MODE', false),
    'placeholder_image' => env('POWERBI_PLACEHOLDER_IMAGE', 'https://example.com/path/to/placeholder-graph-image.jpg'),
];
