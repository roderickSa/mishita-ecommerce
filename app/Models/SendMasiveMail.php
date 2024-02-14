<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMasiveMail extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'sent'];
}
