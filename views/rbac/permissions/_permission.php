<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Permission;

/* @var $permissions Permission[] */
?>

<?php if ($permissions): ?>
    <ul>
        <?php foreach ($permissions as $permission): ?>
            <li>
                <div>
                    <h4><span class="label label-primary"><?= Html::encode($permission->name) ?></span></h4>
                    <span class="action-column">
                        <a href="<?= Url::to(['create', 'parent' => $permission->name]) ?>" title="Добавить дочернее разрешение"
                           data-pjax="0"><span class="glyphicon glyphicon-plus"></span></a>
                        <a href="<?= Url::to(['view', 'id' => $permission->name]) ?>" title="Просмотр"
                           data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <!--<a href="<?/*= Url::to(['update', 'id' => $permission->name]) */?>" title="Редактировать"
                           aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>-->
                        <a href="<?= Url::to(['delete', 'id' => $permission->name]) ?>" title="Удалить"
                           aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?"
                           data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                    </span>
                </div>

                <?= $this->render('_permission', ['permissions' => $permission->data]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>