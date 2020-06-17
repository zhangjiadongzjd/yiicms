<?php

namespace userapi\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;

/**
 * 这里注意是继承 yii\rest\ActiveController 因为源码中已经帮我们实现了index/update等方法
 * 以及其访问规则verbs()等，
 * 其他可参考：http://www.yiichina.com/doc/guide/2.0/rest-controllers
 *
 * 权限采用最简单的QueryParamAuth方式
 * 用户角色权限比较复杂，这里没有做
 *
 * @package middleapi\modules\v1\controllers
 */
class BaseController extends ActiveController
{

    // 不需进行token权限认证的方法
    public $optional = [];

    public $user;

    /**
     * ---------------------------------------
     * 构造方法
     *
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @author hlf <phphome@qq.com> 2020/5/21
     * ---------------------------------------
     */
//    public function init () {
//        parent::init();
//        // 多语言，需要在http header中设置 app-language:zh-CN
//        Yii::$app->language = Yii::$app->request->getHeaders()->get('app-language'); //'en-US';
//    }

    /**
     * ---------------------------------------
     * 行为
     *
     * @return array
     *
     * @author hlf <phphome@qq.com> 2020/5/21
     * ---------------------------------------
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // 设置认证方式
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'optional' => $this->optional,
        ];
        return $behaviors;
    }

    /**
     * ---------------------------------------
     * 获取当前登录用户信息
     *
     * @throws \Throwable
     * @author hlf <phphome@qq.com> 2020/5/22
     * ---------------------------------------
     */
    public function getIdentity() {
        $identity = Yii::$app->user->getIdentity();
        $this->user = $identity ? $identity->getAttributes() : null;
    }


}
