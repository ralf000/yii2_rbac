<?php

use common\models\User;
use yii\helpers\Html;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $role Role */
/* @var $permissions Permission[] */

$this->title = $role->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Роли']) ?>

<div class="roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить дочернюю роль', ['create', 'parent' => $role->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Привязать разрешение', ['/rbac/permissions/attach-to-role', 'role' => $role->name], ['class' => 'btn btn-warning']) ?>
        <?php if (!User::isDefaultRole($role->name)): ?>
            <?= Html::a('Удалить роль', ['delete', 'id' => $role->name], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту роль?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $role,
        'attributes' => [
            'name',
        ],
    ]) ?>
    <h3>Разрешения</h3>
    <?= $this->render('_permissions', ['permissions' => $permissions, 'role' => $role->name]) ?>
</div>
