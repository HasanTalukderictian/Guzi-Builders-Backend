<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testominal extends Model
{
    use HasFactory;

    protected $table ='testominal';

    protected $fillable = [
        'name', 'designation', 'image', 'rating', 'comment'
    ];
}
