<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Infolist extends Model
{
    static $TESTIMONIALS = 'testimonials';
    static $CLIENTS = 'clients';


    protected $fillable = [
        'name',
        'weight',
        'description',
        'active'
    ];
    protected $casts = [
        'data'=>'array',
        'active'=>'boolean'
    ];

    public function scopeActive($query){
        return $query->whereActive(true);
    }

    public function scopeTestimonials($query){
        return $query->whereType(self::$TESTIMONIALS);
    }

    public function scopeClients($query){
        return $query->whereType(self::$CLIENTS);
    }

}
