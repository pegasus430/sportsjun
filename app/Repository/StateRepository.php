<?php

namespace App\Repository;

use App\Model\State;

class StateRepository extends ModelRepository
{
    public static $tags='states';
    public static $prefix='states';
    public static $model =State::class;
}
