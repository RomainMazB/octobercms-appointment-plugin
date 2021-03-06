<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentAppointments extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->integer('phone');
        });
    }
    
    public function down()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->dropColumn('phone');
        });
    }
}
