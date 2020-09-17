<?php

use yii\helpers\Html;
use yii\rbac\Role;

/* @var $this yii\web\View */
/* @var $model Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?= Html::beginForm() ?>

    <div class="form-group">
        <?= Html::label('Название') ?>
        <?= Html::textInput('name', $model->name, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
