<?php namespace RomainMazB\Appointment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class AppointmentTypes extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_appointments_appointmenttypes'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RomainMazB.Appointment', 'appointments_main_menu', 'appointment_types_side_menu');
    }
}
