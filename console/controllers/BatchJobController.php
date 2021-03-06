<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

/**
 * backend cli service.
 */
class BatchJobController extends Controller
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        ini_set('date.timezone', 'Asia/Shanghai');
    }

    /**
     * Test
     */
    public function actionTest()
    {
        $this->writeLog("开始任务 Test");
        $this->writeLog("结束任务 Test");
        $q = new \SplQueue();
    }

    /**
     * run log
     */
    public function writeLog($log, $type = "batch-job")
    {
        $log = "[" . date('Y-m-d H:i:s') . "]" . $log . PHP_EOL;
        file_put_contents(Yii::$app->basePath . "/runtime/" . $type . ".log", $log, FILE_APPEND);
    }
}
