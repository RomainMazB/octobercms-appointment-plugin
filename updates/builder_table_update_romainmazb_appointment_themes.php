<?php namespace RomainMazB\Appointment\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRomainmazbAppointmentAppointmentTypes extends Migration
{
    public function up()
    {
        Schema::table('romainmazb_appointment_appointment_types', function($table)
        {
            $table->integer('duration')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('romainmazb_appointment_appointment_types', function($table)
        {
            $table->time('duration')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
