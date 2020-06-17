<?php

namespace userapi\events;

use Yii;
use yii\base\Model;
use yii\base\Event;
/**
 * ContactForm is the model behind the contact form.
 */
class AfterLoginEvent extends Event
{
    public $userId = 0;

    public $after_login;

}
