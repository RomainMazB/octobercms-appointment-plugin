<?php

namespace RomainMazB\Appointment\Classes\Filters;

use RomainMazB\Appointment\Classes\Filters\Filter;
use RomainMazB\Appointment\Classes\Interfaces\FilterInterface;
use RomainMazB\Appointment\Models\OpeningHours as OpeningHoursModel;
use \Carbon\CarbonPeriod;
use \Illuminate\Support\Collection;

class OpeningHours extends Filter implements FilterInterface
{
    public function applyParameters($parameters)
    {
        // Get the opening hours
        $opening_hours = OpeningHoursModel::all();

        // No opening hours, return an empty collection
        if ($opening_hours->isEmpty()) {
            return $this->filtered_collection;
        }

        $opening_hours_days = $opening_hours->groupBy('day_of_the_week')->keys();

        // Parse the initial collection periods date to only those who contain an opening hours
        $days_filtered_collection = new Collection();
        foreach ($this->initial_collection as $period) {
            foreach ($period as $date) {
                if ($opening_hours_days->contains($date->format('w'))) {
                    $opening_hours_on_this_day = $opening_hours->where('day_of_the_week', $date->format('w'));
                    foreach ($opening_hours_on_this_day as $current_opening_hours) {
                        $start_datetime = $date->copy()->setTimeFrom($current_opening_hours->open_at);
                        $end_datetime = $date->copy()->setTimeFrom($current_opening_hours->close_at);
                        $this->filtered_collection->push(
                            CarbonPeriod::create($start_datetime, $current_opening_hours->interval, $end_datetime)
                                                    ->excludeEndDate()
                        );
                    }
                }
            };
        };

        return $this->filtered_collection;
    }
}
