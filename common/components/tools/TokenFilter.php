<?php
namespace common\components\tools;

use Yii;
use yii\base\Component;

/**
 * token渠道区分类
 */
class TokenFilter extends Component
{
    public static function getAccessToken()
    {
        $accessToken = Yii::$app->request->headers['access-token'];
        return $accessToken ? $accessToken : '';
    }

    public static function getOrigin()
    {
        $url = Yii::$app->request->url;
        $url = substr($url,1);
        $origin = strstr($url, '/', TRUE);
        return $origin;
    }
}
