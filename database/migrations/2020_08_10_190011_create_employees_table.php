<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->timestamp('birth_date')->default(null);
            $table->unsignedBigInteger('department_id');
            $table->string('position');
            $table->enum('employment_type', ['full-time', 'hourly-pay']);
            $table->unsignedFloat('work_hours')->nullable();
            $table->unsignedFloat('rate')->nullable();
            $table->unsignedFloat('month_payment')->nullable();

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
