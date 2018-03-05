<?php
namespace common\components\tools;

use Yii;
use yii\base\Component;

/**
 * 导出文件
 */
class LanHelper extends Component
{

    /**
     * 获取完整路径的文件名(处理了中文乱码问题)
     * @param $filename
     * @return mixed
     */
    public static function get_basename($filename)
    {
        return preg_replace('/^.+[\\\\\\/]/', '', $filename);
    }

    /**
     * 处理下载乱码
     * @param $filename
     */
    public static function getDownloadFilename($filename){
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        return '="' . $encoded_filename . '"';
    }
}
