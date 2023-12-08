<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'address', 'district_id'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
