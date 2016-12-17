<?php

namespace App\Repository;


use App\Model\Country;

class CountryRepository extends ModelRepository
{
    public static $tags='countries';
    public static $prefix='countries';
    public static $model =Country::class;
}
