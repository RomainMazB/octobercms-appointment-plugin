<?php namespace RomainMazB\Appointment\Models;

use Model;
use \Carbon\CarbonPeriod;
use \Carbon\Carbon;

/**
 * Model
 */
class Appointment extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'romainmazb_appointment_appointments';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $dates = ['created_at', 'updated_at', 'datetime'];

    public $belongsTo = [
        'appointment_type' => ['RomainMazB\Appointment\Models\AppointmentType']
    ];

    public function getPeriodAttribute()
    {
        return CarbonPeriod::create($this->date, $this->appointment_type->interval);
    }

    /**
     * returns date attribute as a Carbon object
     *
     * @return Carbon
     */
    // public function getDatetimeAttribute()
    // {
    //     return Carbon::createFromTimeString($this->datetime);
    // }
    /**
     * returns end_datetime attribute as a Carbon object
     *
     * @return Carbon
     */
    public function getEndDatetimeAttribute()
    {
        return Carbon::createFromTimeString($this->datetime)->add($this->appointment_type->interval);
    }
}
