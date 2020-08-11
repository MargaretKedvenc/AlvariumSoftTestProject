<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    protected $dates = ['birth_date'];
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getSalary(): float
    {
        if ($this->employment_type === 'full-time') {
            return $this->month_payment;
        }

        return ($this->rate ?: 0) * ($this->work_hours ?: 0);
    }
}
