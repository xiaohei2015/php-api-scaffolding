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

    public static function set($key, $value, $expired_seconds=3600)
    {
        $new_key = self::getPrefix() . md5($key);
        Yii::$app->redis->set($new_key, json_encode($value));
        return Yii::$app->redis->expire($new_key, $expired_seconds);
    }

    public static function get($key)
    {
        return json_decode(Yii::$app->redis->get(self::getPrefix() . md5($key)));
    }
}