<?php

namespace userapi\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\filters\RateLimiter;  //启用速率限制
use userapi\models\LoginForm;
use yii\rest\ActiveController;
use userapi\events\AfterLoginEvent;
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
                    'login',
                ],
            ],
        ]);

        return $behavior;
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
//        var_dump(Yii::$app->user->isGuest);die;
        $logout = Yii::$app->user->logout();
        var_dump(Yii::$app->user->isGuest);die;
        return $logout;
//        var_dump(Yii::$app->user->isGuest);
    }

}
