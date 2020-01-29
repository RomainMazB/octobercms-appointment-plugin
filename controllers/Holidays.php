<?php namespace RomainMazB\Appointment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Holidays extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'manage_opening_hours_and_holidays' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RomainMazB.Appointment', 'appointments_main_menu', 'manage_opening_hours_and_holidays_side_menu');
    }
}
