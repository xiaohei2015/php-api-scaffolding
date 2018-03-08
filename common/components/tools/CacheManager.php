<?php

namespace common\components\tools;

use Yii;
use yii\base\Component;

class CacheManager extends Component
{
    public static function getPrefix()
    {
        return Yii::$app->id.'.'.Yii::$app->params['cache.prefix'];
    }

    public static function set($key, $value)
    {
        return Yii::$app->redis->set(self::getPrefix() . md5($key), json_encode($value));
    }

    public static function get($key)
    {
        return json_decode(Yii::$app->redis->get(self::getPrefix() . md5($key)));
    }
}