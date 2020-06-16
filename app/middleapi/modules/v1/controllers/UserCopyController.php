<?php

namespace middleapi\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use middleapi\models\LoginForm;
use yii\filters\RateLimiter;
use middleapi\components\BaseController;


class UserCopyController extends BaseController
{
    public $modelClass = 'common\models\UserCopy';

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

        $access_token = $model->login();
        if ($access_token) {
            return ['access-token' => $access_token];
        }
        else {
            $model->validate();
            return $model;
        }
    }

    /**
     * @inheritdoc
     * 根据user_backend表的主键（id）获取用户
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * 根据access_token获取用户，我们暂时先不实现，我们在文章 http://www.manks.top/yii2-restful-api.html 有过实现，如果你感兴趣的话可以看看
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * 用以标识 Yii::$app->user->id 的返回值
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * 验证auth_key
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
