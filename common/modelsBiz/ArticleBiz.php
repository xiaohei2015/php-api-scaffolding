<?php

namespace common\modelsBiz;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Article;
use \common\components\exception\ExceptionHandler;

/**
 * ArticleBiz represents the model behind the search form of `common\models\Article`.
 */
class ArticleBiz extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['title', 'content',],'required','message'=>'{attribute}不能为空'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted]);

        return $dataProvider;
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord) {
                $this->create_time = time();
                $this->update_time = time();
            }else{
                $this->update_time = time();
            }
            return true;
        }else{
            return false;
        }
    }

    public static function createArticle($params)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = new ArticleBiz();
            $model->load($params,'');
            if(!$model->save()){
                ExceptionHandler::throwException($model);
            }

            $model_log = new ArticleLogBiz();
            $model_log->article_id = $model->id;
            $model_log->title = $model->title.$model->title;
            $model_log->content = $model->content;
            if(!$model_log->save()){
                ExceptionHandler::throwException($model_log);
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }

        return true;
    }
}
