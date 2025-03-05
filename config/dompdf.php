<?php

return [
    'font_dir' => storage_path('fonts/'), // Папка для шрифтов
    'font_cache' => storage_path('fonts/'), // Папка для кэша шрифтов
    'custom_font_data' => [
        'dejavusans' => [
            'R' => 'DejaVuSans.ttf', // Regular
            'B' => 'DejaVuSans-Bold.ttf', // Bold
            'I' => 'DejaVuSans-Oblique.ttf', // Italic
            'BI' => 'DejaVuSans-BoldOblique.ttf', // Bold Italic
        ],
        'dejavusanscondensed' => [
            'R' => 'DejaVuSansCondensed.ttf', // Regular
            'B' => 'DejaVuSansCondensed-Bold.ttf', // Bold
            'I' => 'DejaVuSansCondensed-Oblique.ttf', // Italic
            'BI' => 'DejaVuSansCondensed-BoldOblique.ttf', // Bold Italic
        ],
    ],
];