<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class offices extends Model
{
    protected $fillable = [
        'office_name',
        'office_phone',
        'office_address',
        'office_lat',
        'office_long',
        'office_status'
    ];

    protected $appends = ['office_status_label'];

    public function getofficestatuslabelAttribute()
    {
        switch ($this->office_status) {
            case '1':
                $label = "Active";
                break;

            default:
                $label = "Inactive";
                break;
        }
        return $label;
    }
}
