<?php

namespace RomainMazB\Appointment\Classes\Filters;

use RomainMazB\Appointment\Classes\Filters\Filter;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;
use RomainMazB\Appointment\Models\Appointment;
use Carbon\CarbonPeriod;

class Available extends Filter implements FilterInterface
{
    public function applyParameters($parameters)
    {
        $from_date = $this->initial_collection->first()->getStartDate();
        $to_date = $this->initial_collection->last()->getEndDate();
        $booked_appointments = Appointment::whereBetween('datetime', [$from_date, $to_date])
        ->orderBy('datetime', 'ASC')
        ->get();

        // No booked appointment yet, return unmodified collection
        if ($booked_appointments->isEmpty()) {
            return $this->initial_collection;
        }

        foreach ($this->initial_collection as $period) {
            $booked_appointments_in_period = $booked_appointments->filter(function ($appointment) use ($period) {
                return $appointment->datetime->isBetween($period->getStartDate(), $period->getEndDate());
            });
            // dd($booked_appointments->pluck('datetime'));
            // No booked appointment yet in the current period, push unmodified period and continue
            if ($booked_appointments_in_period->isEmpty()) {
                $this->filtered_collection->push($period);
                continue;
            }

            // Go through all booked appointments to modify the period
            foreach ($booked_appointments_in_period as $booked_appointment) {
                $period_start_date = $period->getStartDate();
                // If the first appointment does not start on the parsed period stat date, create an available period before it
                if ($period_start_date->lessThan($booked_appointment->datetime)) {
                    $before_interval = $period_start_date->diffAsCarbonInterval($booked_appointment->datetime);
                    $this->filtered_collection->push(
                        CarbonPeriod::create($period_start_date, $before_interval, $booked_appointment->datetime)->excludeEndDate()
                    );
                }
                // Then set the period start date at the end of appointment
                $period->setStartDate($booked_appointment->end_datetime);
            }

            // At the end of all appointments, if it remains some available time, create an ending available period
            if ($period->getStartDate()->lessThan($period->getEndDate())) {
                $period->setDateInterval($period->getStartDate()->diffAsCarbonInterval($period->getEndDate()));
                $this->filtered_collection->push($period->excludeEndDate());
            }
        }

        return $this->filtered_collection;
    }
}
