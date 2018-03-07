<?php

namespace common\modelsBiz;

use Yii;
use yii\base\Model;

/**
 *
 */
class AdminUserBiz extends \common\models\AdminUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
