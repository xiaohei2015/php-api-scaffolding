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
    private $code = 1;

    public function add($attribute, $label)
    {
        $this->error[$attribute] = $label;
    }

    public function addMulti($err)
    {
        if(is_array($err) || is_object($err)){
            foreach ($err as $k=>$v){
                $this->error[$k] = $v;
            }
        }
    }

    public function getAll()
    {
        return $this->error;
    }

    public function getCode()
    {
        return $this->code;
    }
}