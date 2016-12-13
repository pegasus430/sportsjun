<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentGateWays extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $table = 'payment_gate_ways';
    protected $fillable = [
        'id',
        'name',
        'status',
        'country_id',
        'created_at',
        'updated_at'
    ];
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function paymentSetups()
    {
        return $this->hasMany('App\Model\PaymentSetups', 'gateway_id', 'id');
    }

}
