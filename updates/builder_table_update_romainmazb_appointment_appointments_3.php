<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentAppointments3 extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->dropColumn('deleted_at');
        });
    }
}
