<?php namespace RomainMazB\Appointment\Models;

use Model;
use \Carbon\CarbonInterval;

/**
 * Model
 */
class AppointmentType extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'romainmazb_appointment_appointment_types';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function getIntervalAttribute()
    {
        return CarbonInterval::minutes($this->duration);
    }

    public $hasMany = [
        'appointments' => ['RomainMazB\Appointment\Models\Appointment', 'key' => 'id', 'otherKey' => 'appointment_type_id']
    ];
}
