<?php
namespace common\components\tools;

use Yii;
use yii\base\Component;

/**
 * 权限
 */
class AuthFilter extends Component
{
    public static function getAdminAccessRoles()
    {
        return ['子管理员'];
    }

    public static function getConsultAccessRoles()
    {
        return ['顾问'];
    }

    public static function isAdminAccess()
    {
        if(Yii::$app->user->id){
            $roles = self::getRoles();
            foreach($roles as $v){
                if(in_array($v, self::getAdminAccessRoles())){
                    return true;
                }
            }
        }
        return false;
    }

    public static function isConsultAccess()
    {
        if(Yii::$app->user->id){
            $roles = self::getRoles();
            foreach($roles as $v){
                if(in_array($v, self::getConsultAccessRoles())){
                    return true;
                }
            }
        }
        return false;
    }

    public static function isAdminAccessByUserId($user_id)
    {
        if($user_id){
            $roles = self::getRolesByUserId($user_id);
            foreach($roles as $v){
                if(in_array($v, self::getAdminAccessRoles())){
                    return true;
                }
            }
        }
        return false;
    }

    public static function getRoles()
    {
        return self::getRolesByUserId(Yii::$app->user->id);
    }

    public static function getRolesByUserId($user_id)
    {
        if($user_id) {
            $models = \common\modelsBiz\AuthAssignmentBiz::find()->andFilterWhere(['user_id'=>$user_id])->asArray()->all();
            return array_column($models,'item_name');
        }else{
            return [];
        }
    }
}
