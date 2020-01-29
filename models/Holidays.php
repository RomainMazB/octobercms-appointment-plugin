<?php namespace RomainMazB\Appointment\Models;

use Model;
use \Carbon\Carbon;

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
     * @param String $date
     *
     * @return Carbon
     */
    public function getDateStartAttribute($date)
    {
        return $this->date_start = Carbon::createFromTimeString($date);
    }

    /**
     * returns date_end attribute as a Carbon object
     *
     * @param String $date
     *
     * @return Carbon
     */
    public function getDateEndAttribute($date)
    {
        return $this->date_end = Carbon::createFromTimeString($date);
    }
}
