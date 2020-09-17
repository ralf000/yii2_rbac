<?php

use yii\helpers\Html;
use yii\rbac\Permission;


/* @var $this yii\web\View */
/* @var $model Permission */

$this->title = 'Добавление разрешения';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Разрешения']) ?>

<div class="permission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
