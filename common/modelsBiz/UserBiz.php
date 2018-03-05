<?php

namespace common\modelsBiz;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;

/**
 * SysUserBiz represents the model behind the search form about `common\models\SysUser`.
 */
class UserBiz extends User implements IdentityInterface, RateLimitInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_FROZEN = 2;
    const STATUS_INAUDIT = 3;
    const STATUS_UNCONFIRMED = 0;

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

    public function fields()
    {
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['password'], $fields['token_expired_time'], $fields['function'], $fields['is_deleted'], $fields['allowance'], $fields['allowance_updated_at']);
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['token' => $token]);
        if($user && $user->token_expired_time >= time()){
            $user->setTokenExpiredTime();
            $user->save();
            return $user;
        }else
            return false;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * access-token设置
     */
    public function setAccessToken()
    {
        $token = substr(md5(Yii::$app->security->generateRandomString()),0,16);
        $this->token = $token;
        $this->setTokenExpiredTime();
    }

    /**
     * 移除access-token
     */
    public function removeAccessToken()
    {
        $this->token = '';
        $this->token_expired_time = 0;
    }

    /**
     * access-token过期时间刷新
     */
    public function setTokenExpiredTime()
    {
        $this->token_expired_time = time()+Yii::$app->params['user.token.expired_time'];
    }

    /**
     * 设置最近登录时间
     */
    public function setLastLoginTime()
    {
        $this->last_login = time();
    }

    /**
     * 速率限制
     */
    public function getRateLimit($request, $action)
    {
        return Yii::$app->params['user.rate.limit']; // $rateLimit requests per second
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sex' => $this->sex,
            'status' => $this->status,
            'balance' => $this->balance,
            'token_expired_time' => $this->token_expired_time,
            'add_time' => $this->add_time,
            'last_login' => $this->last_login,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'id_front', $this->id_front])
            ->andFilterWhere(['like', 'id_back', $this->id_back])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'function', $this->function])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }

    public static function findByPhone($phone)
    {
        return self::findOne(['phone'=>$phone,'status'=>[0,1,3]]);
    }

    public static function findByUserId($user_id)
    {
        return self::findOne(['id'=>$user_id,'status'=>[0,1,3]]);
    }

    public static function getUserInfo()
    {
        $data = [];
        if(Yii::$app->user->id){
            $data = Yii::$app->user->getIdentity();
        }
        return $data;
    }

    public static function findByUsername($username)
    {
        if(preg_match('/^1[0-9]{10}$/',$username)){
            return static::findOne(['phone' => $username, 'status' => self::STATUS_ACTIVE]);
        }else {
            return static::findOne(['account' => $username, 'status' => self::STATUS_ACTIVE]);
        }
    }
}
