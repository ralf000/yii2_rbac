<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Role;
use yii\web\View;

/** @var $this View */
/** @var $roles Role[] */
?>

<?php if ($roles): ?>
    <ul>
        <?php foreach ($roles as $role): ?>
            <li>
                <div>
                    <h4><span class="label label-primary"><?= Html::encode($role->name) ?></span></h4>
                    <span class="action-column">
                        <a href="<?= Url::to(['create', 'parent' => $role->name]) ?>" title="Добавить дочернюю роль"
                           data-pjax="0"><span class="glyphicon glyphicon-plus"></span></a>
                        <a href="<?= Url::to(['/rbac/permissions/attach-to-role', 'role' => $role->name]) ?>" title="Привязать разрешение"
                           data-pjax="0"><span class="glyphicon glyphicon-flag"></span></a>
                        <a href="<?= Url::to(['view', 'id' => $role->name]) ?>" title="Просмотр"
                           data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <!--<a href="<?/*= Url::to(['update', 'id' => $role->name]) */?>" title="Редактировать"
                           aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>-->
                        <?php if (!User::isDefaultRole($role->name)): ?>
                            <a href="<?= Url::to(['delete', 'id' => $role->name]) ?>" title="Удалить"
                               aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?"
                               data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php endif; ?>
                    </span>
                </div>

                <?= $this->render('_role', ['roles' => $role->data['roles']]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>