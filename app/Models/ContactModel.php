<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    use HasFactory;

    protected $table ='table_contact';

    protected $fillable = [
        "name", "email", "phone", "subject", "message"
    ];
}
