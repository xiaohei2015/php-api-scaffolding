<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_article".
 *
 * @property int $id 文章编号
 * @property string $title 文章标题
 * @property string $content 文章内容
 * @property int $status 0：未发布 1：已发布
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $is_deleted 是否删除(0:否,1:是)
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['create_time', 'update_time'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['status', 'is_deleted'], 'integer', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '文章编号',
            'title' => '文章标题',
            'content' => '文章内容',
            'status' => '0：未发布 1：已发布',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'is_deleted' => '是否删除(0:否,1:是)',
        ];
    }
}
