<?php

namespace middleapi\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\captcha\CaptchaValidator;
use yii\captcha\CaptchaAction;

class VerifyController extends ActiveController
{

    public $modelClass = '';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['yzm','ValidateYzm','captcha'],
                        'allow' => true,
                    ],
                ]
            ]
        ];
    }

    /**
     * 获取验证码
     * @return mixed
     * @throws yii\base\InvalidConfigException
     */
    public function actionYzm ()
    {
        $c = Yii::createObject('yii\captcha\CaptchaAction', ['captcha', $this]);

        $code = $c->getVerifyCode(true);
//        $c->run();
        return $code;
    }

    public function actionValidateYzm ()
    {
        $caprcha = new CaptchaValidator();

//        $result = $caprcha->validate($code,false);
        if(!$caprcha->validate(Yii::$app->request->post('verifyCode'))){
            $this->addError('111', '1231313');
        }
    }

    public function actions()
    {

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
                'class' => 'middleapi\security\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//                'fixedVerifyCode' => null,

            ],
        ];
    }

//    public function actionIndex()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(setting::ADMIN_EMAIL_ADDRESS)) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        } else {
//            return $this->render('index', [
//                'model' => $model,
//            ]);
//        }
//    }
}
