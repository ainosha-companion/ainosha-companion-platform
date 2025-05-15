<?php

declare(strict_types=1);

return [
    'daily_reward' => 5,

    'streak_bonuses' => [
        [
            'days' => 7,
            'type' => 'weekly',
            'diamonds' => 10,
            'repeatable' => true
        ],
        [
            'days' => 30,
            'type' => 'monthly',
            'diamonds' => 20,
            'repeatable' => false
        ]
    ],

    'timezone' => 'UTC' // Timezone mặc định
];
