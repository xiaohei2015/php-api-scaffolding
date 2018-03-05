<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_user".
 *
 * @property int $id 用户编号
 * @property string $account 账号
 * @property string $auth_key 记住我的认证
 * @property string $password 密码
 * @property string $name 姓名
 * @property int $sex 性别(1:男,2:女)
 * @property string $avatar 头像
 * @property string $phone 手机号码
 * @property int $status 状态(0:未认证,1:审核通过,2:审核失败)
 * @property int $create_time 添加时间
 * @property int $update_time 更新时间
 * @property int $last_login 最后登录时间
 * @property int $is_deleted 是否删除(0:否,1:是)
 * @property string $token 令牌
 * @property int $token_expired_time 令牌过期时间
 * @property int $allowance 速率限制数量
 * @property int $allowance_updated_at 速率限制更新时间
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'last_login', 'token_expired_time', 'allowance', 'allowance_updated_at','sex', 'status', 'is_deleted'], 'integer'],
            [['account', 'password', 'avatar'], 'string', 'max' => 128],
            [['auth_key', 'token'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户编号',
            'account' => '账号',
            'auth_key' => '记住我的认证',
            'password' => '密码',
            'name' => '姓名',
            'sex' => '性别(1:男,2:女)',
            'avatar' => '头像',
            'phone' => '手机号码',
            'status' => '状态(0:未认证,1:审核通过,2:审核失败)',
            'create_time' => '添加时间',
            'update_time' => '更新时间',
            'last_login' => '最后登录时间',
            'is_deleted' => '是否删除(0:否,1:是)',
            'token' => '令牌',
            'token_expired_time' => '令牌过期时间',
            'allowance' => '速率限制数量',
            'allowance_updated_at' => '速率限制更新时间',
        ];
    }
}
