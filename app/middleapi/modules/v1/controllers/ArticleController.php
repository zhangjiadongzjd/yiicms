<?php

namespace middleapi\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class ArticleController extends ActiveController
{	
    public $modelClass = 'common\models\Article';
	
    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [
                'authenticator' => [
                    'class' => QueryParamAuth::className(),
                    'optional' => [
                        'login',
                    ],

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
