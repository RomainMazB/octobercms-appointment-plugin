<?php

namespace RomainMazB\Appointment\Components;

use Lang;
use RomainMazB\Appointment\Classes\Organizer;
use RomainMazB\Appointment\Models\AppointmentType;
use October\Rain\Argon\Argon;
use Config;

class Calendar extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        Config::all();
        return [
            'name' => Lang::get('romainmazb.appointment::lang.components.calendar.name'),
            'description' => Lang::get('romainmazb.appointment::lang.components.calendar.description')
        ];
    }

    /**
     * Called by AJAX when an available date is picked up
     * $this->page is an Organizer filled with available appointments on the date,
     * passed into the partial
     *
     * @return void
     */
    public function onSelectDate()
    {
        $appointment_type = AppointmentType::where('id', post('appointment_type'))->firstOrFail();
        $organizer = new Organizer([]);
        $this->page['next_available_dates'] = $organizer->onOpeningHours(4)->forAppointmentType($appointment_type)->getDates()->all();
    }

    /**
     * Returns all appointment types
     * Used to render the appointment types in views
     *
     * @return AppointmentType
     */
    public function appointmentTypes() {
        return AppointmentType::all();
    }
}
