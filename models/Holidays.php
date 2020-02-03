<?php namespace RomainMazB\Appointment\Models;

use Model;
use \Carbon\Carbon;
use App;

/**
 * Model
 */
class Holidays extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'romainmazb_appointment_holidays';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];



    /**
     * returns date_start a datetime String when running in backend, and Carbon object in frontend
     *
     * @param String $date_start
     *
     * @return Carbon
     */
    public function getDateStartAttribute($date_start)
    {
        if (App::runningInBackend())
            return $date_start;

        return Carbon::createFromTimeString($date_start);
    }

    /**
     * returns date_end a datetime String when running in backend, and Carbon object in frontend
     *
     * @param String $date_end
     *
     * @return Carbon
     */
    public function getDateEndAttribute($date_end)
    {
        if (App::runningInBackend())
            return $date_end;

        return Carbon::createFromTimeString($date_end);
    }
}
