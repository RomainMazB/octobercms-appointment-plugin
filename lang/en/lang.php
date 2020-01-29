<?php return [
    'plugin' => [
        'name' => 'Appointment',
        'description' => 'Let your client resverve appointment with your service',
    ],
    'appointments' => [
        'labels' => [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email address',
            'date' => 'Date',
            'message' => 'Message',
            'phone' => 'Phone number',
            'booked_at' => 'Booked at'
        ],
    ],
    'appointment_types' => [
        'labels' => [
            'name' => 'Subject',
            'duration' => 'Time',
            'description' => 'Description',
            'price' => 'Price',
        ],
        'labels_helpers' => [
            'duration' => 'In minutes.',
            'price' => 'Without currency. Let it empty for free.',
        ],
        'placeholders' => [
            'duration' => 'Eg: 30 for 30 minutes',
            'price' => 'Eg: \'23,55\' pour 25$ et 55 cents',
        ],
    ],
    'days' => [
        'labels' => [
            'date_start' => 'Start on',
            'date_end' => 'End on',
        ],
    ],
    'opening_hours' => [
        'labels' => [
            'open_at' => 'Open at',
            'close_at' => 'Close at',
            'day_of_the_week' => 'Day of the week'
        ],
    ],
    'holidays' => [
        'labels' => [
            'date_start' => 'Holidays start on (included)',
            'date_end' => 'Holidays end on (included)',
        ],
    ],
    'permissions' => [
        'manage_appointments_appointmenttypes' => [
            'label' => 'Manage appointment\'s types',
            'tab_title' => 'Appointment types',
        ],
        'display_appointments' => [
            'label' => 'Display appointments',
            'tab_title' => 'Appointments',
        ],
        'manage_opening_hours_and_holidays' => [
            'label' => 'Allows to manage opening hours',
            'tab_title' => 'Manage opening hours',
        ],
    ],
    'backend_menus' => [
        'manage_appointments_appointment_types' => [
            'label' => 'Types',
        ],
        'display_appointments' => [
            'label' => 'Appointments',
        ],
        'manage_opening_hours' => [
            'label' => 'Opening hours',
        ],
        'manage_holidays' => [
            'label' => 'Holidays',
        ],
    ],
    'components' => [
        'calendar' => [
            'name' => 'Calendar',
            'description' => 'Display a calendar to pick up an appointments',
            'next_appointments_proposal' => 'Here is the next dates availables',
        ],
    ],
    'general' => [
        'date_format' => 'Y-m-d(D)',
        'time_format' => 'h:i',
        'time_range_separator' => 'to',
        'time_range_prefix' => 'from',
        'date_on_prefix' => 'on',
        'time_at_prefix' => 'at',
        'date_format' => 'F jS, Y', // PHP Date format
        'time_format' => 'h A', // PHP Time format
        'days_name' => [
            'day1' => 'Monday',
            'day2' => 'Tuesday',
            'day3' => 'Wednesday',
            'day4' => 'Thursday',
            'day5' => 'Friday',
            'day6' => 'Saturday',
            'day7' => 'Sunday',
        ]
    ],
    'romainmazb' => [
        'appointment::lang' => [
            'appointments' => [
                'labels' => [
                    'email' => 'email address',
                ],
            ],
        ],
    ],
];
