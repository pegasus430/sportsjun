<?php

namespace App\Repository;

use App\Model\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ModelRepository
{
    public static $pack =1000;

    public static function getModel($id){
        $pack = static::$pack;
        $pos = intval($id/$pack);
        if(\Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $cache = \Cache::tags(static::$tags);
        } else {
            $cache = Cache::getFacadeRoot();
        }

        $model = static::$model;
        $collection = $cache->remember(static::$prefix.'_'.$pos, 10, function() use($pos,$pack,$model) {
            return $model::offset($pos*$pack)->limit($pack)->get()->keyBy('id');
        });

        return array_get($collection,$id);
    }

    public static function idList($name){
        if(\Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $cache = \Cache::tags(static::$tags);
        } else {
            $cache = Cache::getFacadeRoot();
        }
        $model = static::$model;

        return $cache->remember(static::$prefix.'_IN_'.$name,10,function() use($model,$name){
           return $model::select(['id',$name])->get()->lists($name,'id');
        });

    }
}
