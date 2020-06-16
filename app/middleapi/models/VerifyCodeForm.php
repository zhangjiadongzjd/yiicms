<?php
namespace middleapi\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class VerifyCodeForm extends Model
{
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['verifyCode', 'required','message' => '验证码不能为空'], //验证码
            ['verifyCode', 'captcha','captchaAction'=>'v1/captcha/validate-yzm','on'=>['checkCode']], //验证码
//            ['verifyCode','captcha','captchaAction'=>'user/captcha','on'=>['login']]
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function checkCode()
    {
//        var_dump(Yii::$app->request->post('verifyCode'));die;
        if ($this->validate()) {
            echo 33;die;
            return  true;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserCopy::findByUsername($this->username);
        }

        return $this->_user;
    }
}
