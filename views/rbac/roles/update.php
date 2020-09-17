<?php

use yii\helpers\Html;
use yii\rbac\Role;

/* @var $this yii\web\View */
/* @var $model Role */

$this->title = 'Редактирование роли: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Роли']) ?>

<div class="role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
