<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

/**
 * backend cli service.
 */
class ServiceController extends Controller
{
    /**
     * 运行
     */
    public function actionStartService()
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        $serv = new \swoole_server("127.0.0.1", 9500);
        $serv->set(array(
            'reactor_num' => 2,
            'worker_num' => 3,
            'task_ipc_mode' => 1,
            'task_worker_num' => 3,
            'dispatch_mode' => 1,
            'daemonize' => 1,
            'log_file' => Yii::$app->basePath . '/runtime/swoole-' . date('Ym') . '.log',
        ));
        $serv->on('Start', array($this, 'onStart'));
        $serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $serv->on('Task', array($this, 'onTask'));
        $serv->on('Finish', array($this, 'onFinish'));
        $serv->on('Receive', array($this, 'onReceive'));
        $serv->start();
    }

    function onStart($serv)
    {
        $this->writeLog("MasterPid={$serv->master_pid}|Manager_pid={$serv->manager_pid}");
        $this->writeLog("Server: start Swoole version is [" . SWOOLE_VERSION . "]");
    }

    function onWorkerStart($serv, $worker_id)
    {
        if (!$serv->taskworker) {
            if ($worker_id == 0) {
                $this->writeLog("onWorkerStart:[$worker_id], data:[]");
                $serv->tick(1000, function ($id) use ($serv, $worker_id) {
                    $serv->task('xiaohei_1');
                    $serv->task('xiaohei_2');
                    $serv->task('xiaohei_3');
                    $this->writeLog("onTick1:[$id], worker_id:$worker_id], thread_id:".getmypid());
                });
            } elseif ($worker_id == 1) {
                //定时发送消息
                $serv->tick(1000, function ($id) use ($serv, $worker_id) {
                    $this->writeLog("onTick2:[$id], worker_id:$worker_id], thread_id:".getmypid());
                });
            } else {
                //发送短信
                $serv->tick(1000, function ($id) use ($serv, $worker_id) {
                    $this->writeLog("onTick3:[$id], worker_id:$worker_id], thread_id:".getmypid());
                });
            }
        } else {
            $this->writeLog("Master worker, worker_id:$worker_id, thread_id:".getmypid());
        }
    }

    function onTask(\swoole_server $serv, $task_id, $from_id, $data)
    {
        $this->writeLog("onTask:[$task_id], from_id:[$from_id], data:[$data], thread_id:".getmypid());
        //开始任务处理
        $this->sendEmail($data);
        $serv->finish($data);
    }

    function onFinish(\swoole_server $serv, $task_id, $data)
    {
        $this->writeLog("onFinish:[$task_id], data:[$data]");
    }

    function sendEmail($data)
    {
        $this->writeLog("sendEmail:This is sendEmail function, data:[$data]");
    }

    /**
     * run log
     */
    public function writeLog($log, $type = "service")
    {
        $log = "[" . date('Y-m-d H:i:s') . "]" . $log . PHP_EOL;
        file_put_contents(Yii::$app->basePath . "/runtime/" . $type . ".log", $log, FILE_APPEND);
    }
}
