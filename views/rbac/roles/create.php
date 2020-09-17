<?php

use yii\helpers\Html;
use yii\rbac\Role;


/* @var $this yii\web\View */
/* @var $model Role */

$this->title = 'Добавление роли';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Роли']) ?>

<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
