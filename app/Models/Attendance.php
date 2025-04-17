<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'office_id',
        'lat_from_employee',
        'long_from_employee',
        'lat_from_office',
        'long_from_office',
        'attendance_time',
        'attendance_out_time',
        'status',
        'description'
    ];

    protected $appends = ['status_label'];

    public function getstatuslabelAttribute()
    {
        switch ($this->status) {
            case '1':
                return "Attendance In And Out";

            case '0':
                return "Attendance In";

            default:
                return "absent";
        }
        // No Break, Return makes sure that the function returns a value therefore no need for break as it will be unreachable
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
    public function office()
    {
        return $this->belongsTo(Offices::class, 'office_id');
    }

}
