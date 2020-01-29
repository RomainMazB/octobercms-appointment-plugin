<?php namespace RomainMazB\Appointment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Appointments extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\ReorderController','Backend\Behaviors\RelationController'];

    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $requiredPermissions = [
        'display_appointments'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RomainMazB.Appointment', 'appointments_main_menu', 'appointments_side_menu');
    }
}
