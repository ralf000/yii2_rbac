<?php

use yii\helpers\Html;
use yii\rbac\Permission;

/* @var $this yii\web\View */
/* @var $permissions Permission[] */

$this->title = 'Разрешения';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Разрешения']) ?>

<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить разрешение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_permission', ['permissions' => $permissions]) ?>

</div>
