<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Infolist extends Model
{
    static $TESTIMONIALS = 'testimonials';
    static $CLIENTS = 'clients';
    static $BANNERS = 'banners';


    protected $fillable = [
        'name',
        'type',
        'weight',
        'description',
        'active',
        'data',
        'image',
        'created_by'
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

    public function scopeBanners($query){
        return $query->whereType(self::$BANNERS);
    }

    public function scopeClients($query){
        return $query->whereType(self::$CLIENTS);
    }

}
