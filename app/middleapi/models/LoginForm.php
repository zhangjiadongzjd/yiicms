<?php
namespace middleapi\models;

use Yii;
use yii\base\Model;
use common\models\UserCopy;
use common\components\ipaddress;
use yii\captcha\CaptchaValidator;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'required','message' => '验证码错误'], //验证码
            ['verifyCode', 'captcha','captchaAction'=>'/v1/verify/captcha'], //验证码
//            ['verifyCode', 'validateCaptcha'], //验证码
        ];
    }

    public function validateCaptcha($attribute, $params)
    {
//        $caprcha = new CaptchaValidator();
//
//        $result = $caprcha->validate($this->verifyCode);
        $caprcha = new CaptchaValidator();

//        $result = $caprcha->validate($code,false);
        if(!$caprcha->validate(Yii::$app->request->post('verifyCode'))){
            $this->addError($attribute, '1231313');
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $accessToken = $this->_user->generateAccessToken(time()+2400);

            $ipaddress = new ipaddress();
			//下面更新用户登录相关信息
			$this->_user->last_login_date = time();
			$this->_user->last_login_ip = Yii::$app->request->getRemoteIP();
			$this->_user->last_login_address = $ipaddress->getIpAddress($this->_user->last_login_ip);
//            $this->_user->validate(['username','password']);
            $this->_user->save();
            return  $accessToken;
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
