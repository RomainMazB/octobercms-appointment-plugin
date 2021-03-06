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
     * returns open_at a datetime String when running in backend, and Carbon object in frontend
     *
     * @param String $open_at
     *
     * @return Carbon
     */
    public function getOpenAtAttribute($open_at)
    {
        if (App::runningInBackend())
            return $open_at;

        return Carbon::createFromTimeString($open_at);
    }

    /**
     * returns  close_at as a datetime String when running in backend, and Carbon object in frontend
     *
     * @param String $close_at
     *
     * @return Carbon
     */
    public function getCloseAtAttribute($close_at)
    {
        if (App::runningInBackend())
            return $close_at;

        return Carbon::createFromTimeString($close_at);
    }

    /**
     * return interval as a CarbonInterval for the current opening hours
     *
     * @return CarbonInterval
     */
    public function getIntervalAttribute()
    {
        return $this->open_at->diffAsCarbonInterval($this->close_at);
    }

    /**
     * returns the value of a week day as a String
     *
     * @return String
     */
    public function getDayOfTheWeekAsStringAttribute()
    {
        return Lang::get('romainmazb.appointment::lang.general.days_name.day' . $this->day_of_the_week);
    }
}
