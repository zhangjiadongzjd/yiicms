<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserCopy */

$this->title = 'Update User Copy: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Copies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-copy-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
