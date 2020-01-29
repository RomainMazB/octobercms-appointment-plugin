<?php namespace RomainMazB\Appointment\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Lang;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class OpeningHours extends Controller
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
        BackendMenu::setContext('RomainMazB.Appointment', 'appointments_main_menu', 'opening_hours_side_menu');
    }

// public function listExtendRecords($records)
// {
//     // Parse the collection the field
//     $parsed_records = $records->getCollection()->transform(function ($record) {
//         // Apply modification, in my case day_of_the_week with string render from lang file
//         $record->day_of_the_week = Lang::get('romainmazb.appointment::lang.opening_hours.labels.day' . $record->day_of_the_week);
//         return $record;
//     });
//     // Return the paginator as it was at the beginning with the modified records
//     return new Paginator($parsed_records, $records->total(), $records->perPage(), $records->currentPage());
// }
}
