<?php
namespace api\modules\v1\controllers;

use common\components\rest\ActiveController;

class Article2Controller extends ActiveController
{
    public $modelClass = 'common\modelsBiz\ArticleBiz';
    public $serializer = [
        'class' => 'common\components\rest\Serializer',
        'collectionEnvelope' => 'list',
    ];
}