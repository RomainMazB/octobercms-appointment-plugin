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
     * returns date_start attribute as a Carbon object
     *
     * @param String $date_start
     *
     * @return Carbon
     */
    public function getDateStartAttribute($date_start)
    {
        if (!App::runningInBackend())
            return Carbon::createFromTimeString($date_start);

        return $this->date_start;
    }

    /**
     * returns date_end attribute as a Carbon object
     *
     * @param String $date_end
     *
     * @return Carbon
     */
    public function getDateEndAttribute($date_end)
    {
        if (!App::runningInBackend())
            return Carbon::createFromTimeString($date_end);

        return $this->date_end;
    }
}
