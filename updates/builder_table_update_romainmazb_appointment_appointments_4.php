<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentAppointments4 extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->renameColumn('date', 'datetime');
        });
    }
    
    public function down()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->renameColumn('datetime', 'date');
        });
    }
}
