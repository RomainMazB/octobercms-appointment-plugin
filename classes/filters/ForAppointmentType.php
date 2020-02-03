<?php

namespace RomainMazB\Appointment\Classes\Filters;

use RomainMazB\Appointment\Classes\Filters\Filter;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;
use RomainMazB\Appointment\Models\AppointmentType;
use Carbon\CarbonPeriod;

class ForAppointmentType extends Filter implements FilterInterface
{
    private $appointment_type;

    public function applyParameters($parameters)
    {
        $this->validateParameters($parameters[0]);
        // Parse all the initial periods
        foreach ($this->initial_collection as $period) {
            // Split the period
            $period->setDateInterval($this->appointment_type_interval);
            // Parse splitted period to create many periods instead of only dates
            foreach ($period as $start_datetime) {
                // Define the end of period by adding the appointment type interval
                $end_datetime = $start_datetime->copy()->add($this->appointment_type_interval);
                // Create the period if it's not overflows
                if ($period->getEndDate()->greaterThanOrEqualTo($end_datetime)) {
                    $this->filtered_collection->push(
                        CarbonPeriod::create($start_datetime, $this->appointment_type_interval, $end_datetime)->excludeEndDate()
                    );
                }
            }
        }

        return $this->filtered_collection;
    }

    /**
     * Validate the appointment type, throw an error if it's not one and then init the filter
     *
     * @param array $parameters
     * @return void
     */
    private function validateParameters(AppointmentType $appointment_type)
    {
        $this->appointment_type = $appointment_type;
        $this->appointment_type_interval = $appointment_type->interval;
    }
}
