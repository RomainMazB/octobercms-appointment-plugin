<?php

namespace RomainMazB\Appointment\Models;

use \Carbon\Carbon;
use \Carbon\CarbonInterval;
use \Carbon\CarbonPeriod;
use Lang;
use Model;
use App;

/**
 * Model
 */
class OpeningHours extends Model
{
    use \October\Rain\Database\Traits\Validation;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'romainmazb_appointment_opening_hours';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    /**
     * returns open_at attribute as a Carbon object
     *
     * @param String $open_at
     *
     * @return Carbon
     */
    public function getOpenAtAttribute($open_at)
    {
        if (!App::runningInBackend())
            return Carbon::createFromTimeString($open_at);

        return $open_at;
    }

    /**
     * returns close_as attribute as a Carbon object
     *
     * @param String $close_at
     *
     * @return Carbon
     */
    public function getCloseAtAttribute($close_at)
    {
        if (!App::runningInBackend())
            return Carbon::createFromTimeString($close_at);

        return $close_at;
    }

    /**
     * return interval for the current opening hours
     *
     * @return CarbonInterval
     */
    public function getIntervalAttribute()
    {
        return $this->open_at->diffAsCarbonInterval($this->close_at);
    }

    /**
     * returns the value of a week day as a string
     *
     * @return String
     */
    public function getDayOfTheWeekAsStringAttribute()
    {
        return Lang::get('romainmazb.appointment::lang.opening_hours.labels.day' . $this->day_of_the_week);
    }

    /**
     * Returns a collection of CarbonInterval within the opening hours
     * Returns null if CarbonInterval range is greater than opening hours one
     *
     * @param CarbonInterval $appointmentInterval
     *
     * @return array|null
     */
    public function splittedByInterval(CarbonInterval $appointmentInterval)
    {
        if ($this->interval->seconds < $appointmentInterval->seconds) // Hacky solution: use greaterThan function with Carbon 2
            return null;

        return collect(CarbonPeriod::create($this->open_at, $appointmentInterval, $this->close_at));
    }
}
