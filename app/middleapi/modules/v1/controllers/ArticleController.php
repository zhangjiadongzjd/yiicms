<?php

namespace middleapi\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Article;
use yii\filters\RateLimiter;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\Redis;

class ArticleController extends ActiveController
{	
    public $modelClass = 'common\models\Article';
	
    public function behaviors() {
        $behavior = parent::behaviors();
//        unset($behavior['rateLimiter']);
//        $behaviors['rateLimiter'] = [
//            'class' => RateLimiter::className(),
//            'enableRateLimitHeaders' => true,
//        ];
        return ArrayHelper::merge ($behavior, [
                'authenticator' => [
                    'class' => QueryParamAuth::className(),
                    'optional' => [
                        'login',
                    ],
                ],
        ] );
    }

    public  function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
//        unset($actions['update']);
        return $actions;
    }

//    public function actionUpdate(){
//        var_dump(Yii::$app->request->post());die;
//    }

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

    public function actionSetCache()
    {
        // 获取 cache 组件
        $cache = Yii::$app->cache;

        // 判断 key 为 username 的缓存是否存在，有则打印，没有则赋值
        $key = 'username';
        if ($cache->exists($key)) {
//            var_dump($cache->get($key));
            return $cache->get($key);
        } else {
            $cache->set($key, 'marko', 60);
        }
    }

    public function actionSetRedis()
    {
        Yii::$app->redis->set('test','hello yii2-reids');  //设置redis缓存
        return Yii::$app->redis->get('test');   //读取redis缓存
    }
}
