<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class,'state_id', 'id');
    }
}
