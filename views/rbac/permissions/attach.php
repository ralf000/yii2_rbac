<?php

use common\helpers\FormsHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Permission;

/* @var $this yii\web\View */
/* @var $role string */
/* @var $permissions Permission[] */

$this->title = "Добавление разрешения для роли {$role}";
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Разрешения']) ?>

<div class="permission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="permission-form">

        <?= Html::beginForm() ?>

        <div class="form-group">
            <?= FormsHelper::select2WithoutModel(
                'permission',
                ArrayHelper::map($permissions, 'name', 'name'),
                'Разрешение'
            ) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?= Html::endForm() ?>

    </div>

</div>
