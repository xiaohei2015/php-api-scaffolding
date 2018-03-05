<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_article_log".
 *
 * @property int $id
 * @property int $article_id 文章编号
 * @property string $title 日志标题
 * @property string $content 日志内容
 * @property int $create_time 创建时间
 */
class ArticleLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_article_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'create_time'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['content'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => '文章编号',
            'title' => '日志标题',
            'content' => '日志内容',
            'create_time' => '创建时间',
        ];
    }
}
