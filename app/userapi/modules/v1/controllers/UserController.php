<?php

namespace userapi\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;  //启用速率限制
use userapi\models\LoginForm;
use yii\rest\ActiveController;
use userapi\events\AfterLoginEvent;
use common\helps\Tools;
use common\models\User;

class UserController extends ActiveController
{

    const EVENT_AFTER_LOGIN = 'after_login';

    public $modelClass = 'common\models\User';

    public function behaviors() {

        $behavior = parent::behaviors();
        unset($behavior['rateLimiter']);
        $behavior['rateLimiter'] = [
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true,
        ];
        unset($behavior['authenticator']);
        ArrayHelper::merge ($behavior, [
            'authenticator' => [
                'class' => QueryParamAuth::className(),
                'optional' => [
                    'login'
                ],
            ],
        ]);

        return $behavior;
    }

    public function beforeAction($action)
    {
        return Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionLogin ()
	{
		$model = new LoginForm;
		$model->setAttributes(Yii::$app->request->post());
		$access_token = $model->login();

        if ($access_token) {
            return ['access-token' => $access_token];
        }
        else {
            $model->validate();
            return $model;
        }
	}

    public function actionLogout()
    {
        $user_id = Yii::$app->user->identity->id;

        $model = new LoginForm;
        $logout = $model->logout($user_id);
        if($logout){
            return Tools::json_data(200,'退出成功');
        }
    }

}
