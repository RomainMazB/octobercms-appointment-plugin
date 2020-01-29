<?php return [
    'plugin' => [
        'name' => 'Rendez-vous',
        'description' => 'Vos clients peuvent prendre en ligne des rendez-vous.',
    ],
    'appointments' => [
        'labels' => [
            'first_name' => 'Nom',
            'last_name' => 'Prénom',
            'email' => 'Adresse mail',
            'date' => 'Date',
            'message' => 'Message',
            'phone' => 'Numéro de téléphone',
        ],
    ],
    'appointment_types' => [
        'labels' => [
            'name' => 'Objet',
            'duration' => 'Durée',
            'description' => 'Description',
            'price' => 'Prix',
        ],
        'labels_helpers' => [
            'duration' => 'Saisir en minutes.',
            'price' => 'Sans la devise. Laisser vide si gratuit.',
        ],
        'placeholders' => [
            'duration' => 'Exemple: 30 pour 30 minutes',
            'price' => 'Exemple: \'23,55\' pour 25€ et 55 cents',
        ],
    ],
    'days' => [
        'labels' => [
            'date_start' => 'Début le',
            'date_end' => 'Fin le',
        ],
    ],
    'openinghours' => [
        'labels' => [
            'open_at' => 'Ouverture à',
            'close_at' => 'Fermeture à',
            'day_of_the_week' => 'Jour de la semaine',
            'day1' => 'Lundi',
            'day2' => 'Mardi',
            'day3' => 'Mercredi',
            'day4' => 'Jeudi',
            'day5' => 'Vendredi',
            'day6' => 'Samedi',
            'day7' => 'Dimanche',
        ],
    ],
    'permissions' => [
        'manage_appointments_appointment_types' => [
            'label' => 'Gérer les thèmes de rendez-vous',
            'tab_title' => 'Thèmes',
        ],
        'display_appointments' => [
            'label' => 'Afficher les rendez-vous',
            'tab_title' => 'Rendez-vous',
        ],
        'manage_opening_hours_and_holidays' => [
            'label' => "Gérer les heures d'ouvertures et vacances",
            'tab_title' => 'Gérer les horaires',
        ]
    ],
    'backend_menus' => [
        'manage_appointments_appointment_types' => [
            'label' => 'Types',
        ],
        'display_appointments' => [
            'label' => 'Rendez-vous',
        ],
        'manage_opening_hours' => [
            'label' => "Heures d'ouvertures",
        ],
        'manage_holidays' => [
            'label' => 'Vacances',
        ]
    ],
    'components' => [
        'calendar' => [
            'name' => 'Calendrier',
            'description' => 'Affiche le calendrier pour la saisie de rendez-vous.'
        ]
    ]
];
