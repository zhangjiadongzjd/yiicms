<?php

namespace middleapi\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use middleapi\models\LoginForm;

class UserCopyController extends ActiveController
{
    public $modelClass = 'common\models\UserCopy';

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
