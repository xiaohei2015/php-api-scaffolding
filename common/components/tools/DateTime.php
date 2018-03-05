<?php
namespace common\components\tools;

use Yii;
use yii\base\Component;

/**
 * DateTime工具箱
 */
class DateTime extends Component
{
    public static function isValid($date, $formats = ["Y-m-d", "Y/m/d", "Ymd", "Y-m-d H:i:s", "Y-m-d H:i"])
    {
        $unixTime = strtotime($date);
        if (false === $unixTime) {
            return false;
        }
        //校验日期的有效性，只要满足其中一个格式就OK
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }

    /**
     * 时间格式化
     */
    public static function formatDate2Str($time)
    {
        $time_diff = time () - $time;
        if ($time_diff < 60) {
            $str = '刚刚';
        } elseif ($time_diff < 60 * 60) {
            $min = floor ( $time_diff / 60 );
            $str = $min . '分钟前';
        } elseif ($time_diff < 60 * 60 * 24) {
            $h = floor ( $time_diff / (60 * 60) );
            $str = $h . '小时前';
        } elseif ($time_diff < 60 * 60 * 24 * 3) {
            $d = floor ( $time_diff / (60 * 60 * 24) );
            if ($d == 1)
                $str = '昨天';
            else
                $str = '前天';
        } elseif ($time_diff < 60 * 60 * 24 * 7) {
            $d = floor ( $time_diff / (60 * 60 * 24) );
            $str = $d . '天前';
        } else {
            $str = date ( "Y-m-d", $time );
        }
        return $str;
    }

    /**
     * 根据出生日期，返回年龄
     * @param $birthday
     * @return int
     */
    public static function getAge($birthday)
    {
        $age = strtotime($birthday);
        if($age === false){
            return 0;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d", $age));
        list($y2,$m2,$d2) = explode("-",date("Y-m-d"), time());
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1)){
            $age -= 1;
        }
        return $age;
    }
}
