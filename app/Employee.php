<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    protected $dates = ['birth_date'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getSalary(): float
    {
        if ($this->employment_type === 'full-time') {
            return $this->month_payment;
        }

        return $this->rate * $this->work_hours;
    }
}
