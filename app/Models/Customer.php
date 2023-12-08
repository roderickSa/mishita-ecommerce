<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone', 'status', 'created_by', 'updated_by'];

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function shipping_addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class)->where('type', '=', AddressType::Shipping);
    }

    public function billing_address(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)->where('type', '=', AddressType::Billing);
    }
}
