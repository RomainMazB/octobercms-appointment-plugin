<?php

namespace RomainMazB\Appointment\Classes;

use \Carbon\Carbon;
use \Carbon\CarbonInterval;
use \Carbon\CarbonPeriod;
use \Illuminate\Support\Collection;
use RomainMazB\Appointment\Models\AppointmentType;
use RomainMazB\Appointment\Models\Appointment;
use RomainMazB\Appointment\Models\OpeningHours;
use RomainMazB\Appointment\Models\Holidays;
use RomainMazB\Appointment\Classes\PeriodInterval;


class Organizer extends Collection
{
    public $day_of_the_week = null;

    public $appointment_type = null;

    public $opening_hours = null;

    public $weeks = 3;

    public $from_date = null;

    public $to_date = null;

    public $interval = null;

    public $on_opening_hours = false;

    public $only_available_periods = true;

    public $on_holidays = false;

    public $booking_deadline = 2;

    /**
     * Specifie the start date for Organizer (including booking deadline), default on today.
     *
     * @param Carbon $date
     * @return Organizer
     */
    public function fromDate(Carbon $date) {
        $this->from_date = $date;

        return $this;
    }

    /**
     * *Remove this function?? only used to default a 1 day for initial interval
     */
    public function setInterval(CarbonInterval $interval) {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Specifies end date, if not, default will be weeks properties, which is set to default 3 (weeks)
     *
     * @param Carbon $date
     * @return Organizer
     */
    public function toDate(Carbon $date) {
        $this->to_date = $date;

        return $this;
    }

    /**
     * Specifies the day of the week to filter, iso days: sunday = 0, monday = 1, and son on
     *
     * @param Int $day_of_the_week
     * @return void
     */
    public function dayOfWeek(Int $day_of_the_week) {
        $this->day_of_the_week = abs($day_of_the_week);

        return $this;
    }

    /**
     * Specifies a booking_deadline in days (default: populate_roles_210(  ))
     *
     * @param Int $booking_deadline
     * @return Organizer
     */
    public function bookingDeadLine(Int $booking_deadline) {
        $this->booking_deadline = abs($booking_deadline);

        return $this;
    }

    public function onOpeningHours(Int $day_of_the_week) {
        $this->on_opening_hours = true;
        $this->dayOfWeek($day_of_the_week);

        return $this;
    }

    public function forWeeks(Int $weeks = 3) {
        $this->weeks = abs($weeks);

        return $this;
    }

    public function forAppointmentType(AppointmentType $appointment_type) {
        $this->appointment_type = $appointment_type;

        return $this;
    }

    /**
     * Return the Organizer
     *
     * @return Organizer
     */
    public function getDates() {
        // Init the from and to date
        if (is_null($this->from_date))
            $this->fromDate(Carbon::today());

        if (is_null($this->to_date))
            $this->toDate($this->from_date->copy()->addWeeks($this->weeks));

        if(is_null($this->interval))
            $this->setInterval(CarbonInterval::days(1));

        // Create daily periods from within the dates to begin
        $periods = $this->mergeItems(
            CarbonPeriod::create($this->from_date, $this->interval, $this->to_date)->excludeEndDate()
                                    ->toArray()
        );
        // Apply filters if set
        if($this->on_opening_hours && $periods->isNotEmpty())
            $periods = $periods->filterOnOpeningHours();

        elseif (isset($this->day_of_the_week) && $periods->isNotEmpty())
            $periods = $periods->filterWithDayOfTheWeek();

        if(! $this->on_holidays && $periods->isNotEmpty())
            $periods = $periods->filterNotInHolidays();

        if($this->only_available_periods && $periods->isNotEmpty())
            $periods = $periods->filterOnlyAvailablePeriods();

        if (isset($this->appointment_type) && $periods->isNotEmpty())
            $periods = $periods->filterForAppointmentType();

        // Return a Organizer
        return  $periods;
    }

    /**
     * Fiter the periods with a specific day, compared to opening hours
     *
     * @return Organizer
     */
    public function filterWithDayOfTheWeek() {
        //Init
        $filtered_collection =  clone $this->empty();
        $day_of_the_week = $this->day_of_the_week;

        $filtered_collection = $this->filter(function($current_period) use ($day_of_the_week) {
            return intval($current_period->format('w')) === $day_of_the_week;
        });

        return $filtered_collection;
    }

    public function filterOnOpeningHours() {
        //Init
        $filtered_collection =  clone $this->empty();
        $opening_hours = OpeningHours::where('day_of_the_week', $this->day_of_the_week)->get();

        // No opening hours on this day, return an empty collection
        if($opening_hours->isEmpty())
            return $filtered_collection;

        $day_filtered_collection = $this->filterWithDayOfTheWeek();

        foreach($day_filtered_collection as $date) {
            foreach($opening_hours as $current_opening_hours) {
                $start_datetime = $date->copy()->setTimeFrom($current_opening_hours->open_at);
                $end_datetime = $date->copy()->setTimeFrom($current_opening_hours->close_at);
                $filtered_collection->push(
                    CarbonPeriod::create($start_datetime, $current_opening_hours->interval, $end_datetime)->excludeEndDate()
                );
            }
        }

        return $filtered_collection;
    }

    /**
     * Filter the periods to extract all already booked appointments and re-organize the periods
     *
     * @return Organizer
     */
    public function filterOnlyAvailablePeriods() {
        // Init
        $booked_appointments = Appointment::where([
                ['datetime', '>=', $this->from_date],
                ['datetime', '<=', $this->to_date]
            ])
            ->orderBy('datetime', 'ASC')
            ->get();

        // No booked appointment yet, return unmodified collection
        if($booked_appointments->isEmpty())
            return $this;

        $filtered_collection = clone $this;
        $filtered_collection = $this->empty();
        foreach($this as $period) {
            $booked_appointments_in_period = $booked_appointments
            ->filter(function($appointment) use ($period) {
                return $appointment->datetime->isBetween($period->getStartDate(), $period->getEndDate());
            });

            if($booked_appointments_in_period->isEmpty()) {
                $filtered_collection->push($period);
                continue;
            }

            foreach($booked_appointments_in_period as $booked_appointment) {
                $period_start_date = $period->getStartDate();
                $before_interval = $period_start_date->diffAsCarbonInterval($booked_appointment->datetime);
                if($period_start_date->lessThan($booked_appointment->datetime)) {
                    $filtered_collection->push(
                        CarbonPeriod::create($period_start_date, $before_interval, $booked_appointment->datetime)->excludeEndDate()
                    );
                }
                $period->setStartDate($booked_appointment->end_datetime);
            }

            if($period->getStartDate()->lessThan($period->getEndDate())) {
                $period->setDateInterval($period->getStartDate()->diffAsCarbonInterval($period->getEndDate()));
                $filtered_collection->push($period->excludeEndDate());
            }
        }
        return $filtered_collection;
    }

    /**
     * Filter the periods with a specific appointment type: the periods will be splitted with the appointment interval
     *
     * @return Organizer
     */
    public function filterForAppointmentType() {
        // Init
        $filtered_collection = $this->empty();
        $appointment_type_interval = $this->appointment_type->interval;
        // Parse all the initial periods
        foreach($this as $initial_period) {
            // Split the period
            $initial_period->setDateInterval($appointment_type_interval);
            // Parse splitted period to create many periods instead of only dates
            foreach($initial_period as $start_datetime) {
                $end_datetime = $start_datetime->copy()->add($appointment_type_interval);
                // Don't create if it overflows
                if($initial_period->getEndDate()->greaterThanOrEqualTo($end_datetime)) {
                    $period = CarbonPeriod::create($start_datetime, $appointment_type_interval, $end_datetime)->excludeEndDate();
                    $filtered_collection->push($period);
                }
            }
        }

        return $filtered_collection;
    }

    /**
     * Filter the periods to extract all the holidays from them
     *
     * @return Organizer
     */
    public function filterNotInHolidays() {
        // Init
        $holidays = Holidays::where([
            ['date_start', '>=', $this->from_date],
            ['date_end', '<=', $this->to_date]
        ])->get();

        // No holidays, return unmodified collection
        if($holidays->isEmpty())
            return $this;

        $filtered_collection = $this->reject(function($current_date) use ($holidays) {
            $date = $current_date->getStartDate();

            foreach($holidays as $holidays_date) {
                if ($date->isBetween($holidays_date->date_start, $holidays_date->date_end))
                    return true;
            }

            return false;
        });

        return $this->empty()->mergeItems($filtered_collection);
    }

    /**
     * Merge collection items but keep actual Organizer properties
     *
     * @param Array $items
     * @return Organizer
     */
    public function mergeItems($items)
    {
        $dates_provider = clone $this;
        $dates_provider->items = $this->merge($items)->all();

        return $dates_provider;
    }

    /**
     * Empty the collection items but keep actual Organizer properties
     *
     * @return Organizer
     */
    public function empty() {

        $dates_provider = clone $this;
        $dates_provider->items = [];

        return $dates_provider;
    }
}
