<?php

namespace userapi\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use userapi\models\LoginForm;
use userapi\controllers\BaseController;
use yii\filters\RateLimiter;

class UserController extends BaseController
{	
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
	
	public function actionLogin ()
	{
		$model = new LoginForm;
		$model->setAttributes(Yii::$app->request->post());
        if ($model->login()) {
            return ['access-token' => $model->login()];
        }
        else {
            $model->validate();
            return $model;
        }
	}
}
