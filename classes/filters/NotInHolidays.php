<?php

namespace RomainMazB\Appointment\Classes\Filters;

use RomainMazB\Appointment\Classes\Filters\Filter;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;
use RomainMazB\Appointment\Models\Holidays;


class NotInHolidays extends Filter implements FilterInterface
{
    public function applyParameters($parameters)
    {
        // Init
        $from_date = $this->initial_collection->first()->getStartDate();
        $to_date = $this->initial_collection->last()->getEndDate();
        $holidays = Holidays::whereBetween('date_start', [$from_date, $to_date])
                                            ->orWhereBetween('date_end', [$from_date, $to_date])
                                            ->get();

        // No holidays, return unmodified collection
        if ($holidays->isEmpty()) {
            return $this->initial_collection;
        }

        // Filter the initial collection to remove the dates which are inside of a holiday period
        $this->filtered_collection = $this->initial_collection->reject(function ($period) use ($holidays) {
            foreach ($holidays as $holidays_date) {
                // Define if the start date or end date is in a holidays period
                // ** WHEN OCTOBERCMS UPDATE ( 2020 MARCH) USE: CarbonPeriod->overlaps method
                $start_date_is_in_holidays = ($period->getStartDate()->isBetween($holidays_date->date_start, $holidays_date->date_end));
                $end_date_is_in_holidays = ($period->getEndDate()->isBetween($holidays_date->date_start, $holidays_date->date_end));

                if ($start_date_is_in_holidays || $end_date_is_in_holidays) {
                    return true;
                }
            }
            return false;
        });

        return $this->filtered_collection;
    }
}
