<?php

namespace common\components\tools;

use Yii;
use yii\base\Component;

class QueueManager extends Component
{
    public static function getPrefix()
    {
        return Yii::$app->id.'.'.Yii::$app->params['queue.prefix'];
    }

    public static function push($queue_name, $data)
    {
        return Yii::$app->redis->rpush(self::getPrefix() . md5($queue_name), json_encode($data));
    }

    public static function pop($queue_name)
    {
        return json_decode(Yii::$app->redis->rpop(self::getPrefix() . md5($queue_name)));
    }
}