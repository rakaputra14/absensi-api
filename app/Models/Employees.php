<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'address',
        'is_active',
        'gender'
    ];

    protected $appends = ['gender_text', 'is_active_label'];

    public function getGendertextAttribute()
    {
        switch ($this->gender) {
            case '1':
                $label = "Male";
                break;

            default:
                $label = "Female";
                break;
        }
        return $label;
    }

    public function getIsActiveLabelAttribute()
    {
        switch ($this->is_active) {
            case '1':
                $label = "Active";
                break;

            default:
                $label = "Inactive";
                break;
        }
        return $label;
    }

    //connecting the foreign key
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}


