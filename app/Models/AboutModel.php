<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutModel extends Model
{
    use HasFactory;

    protected $table ='about_us';

    protected $fillable = [
        'imageUrl','heading', 'description'
    ];
}
