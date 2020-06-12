<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=111.231.24.78;dbname=cms',
            'username' => 'cms',
            'password' => 'y77PNCjHjD5Z8Gjf',
            'charset' => 'utf8',
            'tablePrefix' => 't_',  //数据库表名前缀
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
