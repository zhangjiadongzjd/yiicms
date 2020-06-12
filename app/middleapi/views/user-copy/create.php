<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserCopy */

$this->title = 'Create User Copy';
$this->params['breadcrumbs'][] = ['label' => 'User Copies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-copy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
