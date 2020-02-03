<?php

namespace RomainMazB\Appointment\Classes\Filters;

use RomainMazB\Appointment\Classes\Filters\Filter;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;
use \Illuminate\Support\Collection;
use \Carbon\CarbonPeriod;

class DayOfWeek extends Filter implements FilterInterface
{
    private $day_of_week;

    public function applyParameters($parameters)
    {
        $this->validateParameters($parameters[0]);
        // Loop over all periods in the initial collection
        foreach ($this->initial_collection as $period) {
            $period_collection = new Collection($period->toArray());
            // Push a day period only when day of week is equal at the date's day of week
            foreach($period_collection as $date) {
                if ($date->format('w') == $this->day_of_week) {
                    $this->filtered_collection->push(
                        CarbonPeriod::create($date, $period->getDateInterval(), $date->copy()->add($period->getDateInterval()))
                            ->excludeEndDate()
                    );
                }
            }
        }

        return $this->filtered_collection;
    }

    /**
     * Validate the day_of_week parameter to be an integer between 0 and 6, if not: throw an error
     *
     * @param array $parameters
     * @return void
     */
    private function validateParameters(Int $day_of_week)
    {
        try {
            // Check if parameter is correctly set
            if ($day_of_week < 0 or $day_of_week > 6) {
                throw new \Exception(chr(27) . "[41m" . $day_of_week ." is not a valid integer for day_of_week, it must be >= 0(Sunday) and <= 6 (Saturday)" . chr(27) . "[0m");
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        $this->day_of_week = $day_of_week;
    }
}
