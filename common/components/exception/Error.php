<?php

namespace common\components\exception;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @author Johnny Hu
 */
class Error extends Component
{
    private $error = [];
    private $code = 0;

    public function init()
    {
        parent::init();
        $this->setCode(Yii::$app->params['response.code']['system_error']['id']);
    }

    public function add($err, $code = 0)
    {
        if(is_array($err) || is_object($err)){
            foreach ($err as $k=>$v){
                $this->error[$k] = $v;
            }
        }else{
            $this->error = $err;
        }
        $this->setCode($code);
    }

    public function getAll()
    {
        return $this->error;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }
}