<?php

namespace middleapi\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\captcha\CaptchaValidator;

class CaptchaController extends ActiveController
{

    public $modelClass = '';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['logout', 'signup','login'],//这里一定要加
                'rules' => [
                    [
                        'actions' => ['Yzm','ValidateYzm'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ]
        ];
    }

    public function actionYzm ()
    {
        $c = Yii::createObject('yii\captcha\CaptchaAction', ['__captcha', $this]);

        $code = $c->getVerifyCode(true);
//        $c->run();
        return $code;
    }

    public function actionValidateYzm ($attribute, $params)
    {
        $caprcha = new CaptchaValidator();
        $code = Yii::$app->request->post('verifyCode');
        $caprcha->validate($code);

        var_dump($code);die;
    }
}
