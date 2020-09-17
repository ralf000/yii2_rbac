<?php

use yii\helpers\Html;
use yii\rbac\Permission;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $permission Permission */

$this->title = $permission->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('../../blocks/users/menu', ['active' => 'Разрешения']) ?>

<div class="permission-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $permission->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это разрешение?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $permission,
        'attributes' => [
            'name',
        ],
    ]) ?>

</div>
