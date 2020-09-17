<?php

use yii\helpers\Html;
use yii\rbac\Role;

/* @var $this yii\web\View */
/* @var $roles Role[] */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Роли']) ?>

<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить роль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_role', ['roles' => $roles]) ?>

</div>
