<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\Permission;

/* @var $permissions Permission[] */
/* @var $role string */
?>

<?php if ($permissions): ?>
    <ul>
        <?php foreach ($permissions as $permission): ?>
            <li>
                <div>
                    <h4><span class="label label-primary"><?= Html::encode($permission->name) ?></span></h4>
                    <span class="action-column">
                        <a href="<?= Url::to(['/rbac/permissions/view', 'id' => $permission->name]) ?>" title="Просмотр"
                           data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <a href="<?= Url::to(['/rbac/permissions/detach-from-role', 'role' => $role, 'permission' => $permission->name]) ?>" title="Отвязать"
                           aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?"
                           data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                    </span>
                </div>

                <?= $this->render('_permissions', ['permissions' => $permission->data, 'role' => $role]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>