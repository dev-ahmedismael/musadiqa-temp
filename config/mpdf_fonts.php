<?php
return [
    'custom_font_dir' => storage_path('fonts/'),  // Directory where font files are stored
    'custom_font_data' => [
        'Cairo' => [
            'R'  => 'Cairo-Regular.ttf',    // Regular font
            'B'  => 'Cairo-Bold.ttf',       // Bold font, if available
        ],
    ],
    'default_font' => 'Cairo'
];
