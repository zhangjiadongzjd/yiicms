<?php

namespace userapi\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\models\Article;

class ArticleController extends ActiveController
{	
    public $modelClass = 'common\models\Article';
	
    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [
                'authenticator' => [
                    'class' => QueryParamAuth::className()
                ]
        ] );
    }

    public  function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        var_dump(111);die;
        $modelClass = $this->modelClass;
        return new ActiveDataProvider(
            [
                'query'=>$modelClass::find()->asArray(),
                'pagination'=>['pageSize'=>5],
            ]
        );
    }

    public function actionSearch() {
        return Article::find()->where(['like','title',$_POST['keyword']])->all();
    }
}
