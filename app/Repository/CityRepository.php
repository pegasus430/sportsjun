<?php

namespace App\Repository;

use App\Model\City;

class CityRepository extends ModelRepository
{
    public static $tags='cities';
    public static $prefix='cities';
    public static $model =City::class;
}
