<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


    protected $table ='employees';

    protected $fillable = [
        'employee_id',
        'employee_name',
        'status',
        'joining_date',
        'designation',
        'department',
        'contact_no',
        'image',
    ];

}
