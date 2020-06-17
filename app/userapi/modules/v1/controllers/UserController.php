<?php

namespace userapi\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use userapi\models\LoginForm;
use userapi\controllers\BaseController;
use yii\filters\RateLimiter;
use userapi\events\AfterLoginEvent;
use common\models\User;

class UserController extends BaseController
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
        $this->optional = ['login'];
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

//    public function __construct()
//    {
//        $this->on(self::EVENT_AFTER_LOGIN,['userapi\component\AfterLogin','hello']);
//    }

    public function actionLogin ()
	{
		$model = new LoginForm;
		$model->setAttributes(Yii::$app->request->post());
		$access_token = $model->login();

        if ($access_token) {
//            $event = new AfterLoginEvent;
//            $event->userId = 'after_login';
//            $this->trigger(self::EVENT_AFTER_LOGIN);
            return ['access-token' => $access_token];
        }
        else {
            $model->validate();
            return $model;
        }
	}

}
