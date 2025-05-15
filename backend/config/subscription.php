<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Trial Plan Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the trial plan that is automatically assigned
    | to new users upon registration.
    |
    */

    // The name of the trial plan in the database
    'trial_plan_name' => env('TRIAL_PLAN_NAME', 'trial'),

    // The duration of the trial in days
    'trial_duration_days' => env('TRIAL_DURATION_DAYS', 14),

    'license' => [
        'default_duration_days' => 30, // Default license duration in days (null for unlimited)
    ],
];
