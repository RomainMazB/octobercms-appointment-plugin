<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentOpeningHours extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_opening_hours', function($table)
        {
            $table->renameColumn('day', 'day_of_the_week');
        });
    }
    
    public function down()
    {
        Schema::table('romainmazb_appointment_opening_hours', function($table)
        {
            $table->renameColumn('day_of_the_week', 'day');
        });
    }
}
