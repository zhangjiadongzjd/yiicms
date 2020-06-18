<?php
namespace userapi\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\User;

/**
 * after login behavior
 */
class AfterLoginBehavior extends Behavior
{
    /**
     * @var int
     */
    public $attribute = 'logged_at';

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'afterLogin',
        ];
    }

    /**
     * @param \yii\web\UserEvent $event
     * @return bool
     */
    public function afterLogin($event)
    {
        $model = $event->identity;
        if (!empty($model)) {

            if (!Yii::$app->session->isActive) {
                Yii::$app->session->open();
            }
            Yii::$app->session->set('user'.$model->username, ['id' => $model->id, 'username' => $model->username]);

            Yii::$app->session->close();

        }
        return false;
    }
}