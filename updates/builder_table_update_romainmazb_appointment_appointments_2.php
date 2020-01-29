<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentAppointments2 extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->string('phone', 10)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('romainmazb_appointment_appointments', function($table)
        {
            $table->integer('phone')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
