<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_user_copy".
 *
 * @property int $id
 * @property string $username
 * @property string $nickname 用户昵称
 * @property string $head_pic 用户头像
 * @property string $auth_key
 * @property string $password_hash 密码hash
 * @property string $password_reset_token 重置密码凭据
 * @property string $access_token 用户访问数据凭证
 * @property string $mobile 手机号码
 * @property string $email 用户电子邮箱
 * @property int $status 用户状态
 * @property int $r_id 用户等级
 * @property int $created_at 注册账号时间
 * @property string $created_address 注册账号的地点
 * @property string $created_ip 注册账号的IP
 * @property int $last_login_date 最后一次登录时间
 * @property string $last_login_ip 最后一次登录IP
 * @property string $last_login_address 最后一次登录地点
 * @property int $integral 积分
 * @property string $balance 余额
 * @property int $updated_at 更新时间
 */
class UserCopy extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 't_user_copy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nickname', 'auth_key', 'password_hash', 'r_id', 'created_at', 'updated_at'], 'required'],
            [['status', 'r_id', 'created_at', 'last_login_date', 'integral', 'updated_at'], 'integer'],
            [['balance'], 'number'],
            [['username', 'nickname', 'auth_key'], 'string', 'max' => 32],
            [['head_pic', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 11],
            [['created_address', 'last_login_address'], 'string', 'max' => 200],
            [['created_ip', 'last_login_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'nickname' => 'Nickname',
            'head_pic' => 'Head Pic',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'access_token' => 'Access Token',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'status' => 'Status',
            'r_id' => 'R ID',
            'created_at' => 'Created At',
            'created_address' => 'Created Address',
            'created_ip' => 'Created Ip',
            'last_login_date' => 'Last Login Date',
            'last_login_ip' => 'Last Login Ip',
            'last_login_address' => 'Last Login Address',
            'integral' => 'Integral',
            'balance' => 'Balance',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::find()->where(['access_token'=>$token , 'status' => self::STATUS_ACTIVE])->one();
        if($user){
            return $user;
        }else{
            return null;
        }
    }
    /**
     * @inheritdoc
     */
    public static function loginByAccessToken($token, $type = null)
    {
        $user = static::find()->where(['access_token'=>$token , 'status' => self::STATUS_ACTIVE])->one();
        if($user){
            return $user;
        }else{
            return null;
        }
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email){
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by mobile
     *
     * @param string $mobile
     * @return static|null
     */
    public static function findByMobile($mobile){
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken($expire_time)
    {
        $this->access_token = Yii::$app->security->generateRandomString().'_'.$expire_time;
        return $this->access_token;
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getUserName($id){
        return self::findOne($id)->username;
    }
    /**
     * 获取类别的下拉菜单
     * @return type
     */
    public static function dropDown($r_id){
        $data = self::find()->where(['>','r_id',$r_id])->asArray()->all();
        $data_list = ArrayHelper::map($data, 'id', 'username');
        return $data_list;
    }
}
