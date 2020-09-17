<?php

namespace backend\controllers\rbac;

use backend\components\Controller;
use common\services\rbac\PermissionsService;
use common\services\rbac\RolesService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Permission;
use yii\web\ForbiddenHttpException;

class PermissionsController extends Controller
{
    /**
     * @var PermissionsService
     */
    private $permissionsService;
    /**
     * @var RolesService
     */
    private RolesService $rolesService;

    public function __construct(
        $id,
        $module,
        RolesService $rolesService,
        PermissionsService $permissionsService,
        $config = []
    )
    {
        $this->rolesService = $rolesService;
        $this->permissionsService = $permissionsService;

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $permissions = $this->permissionsService->tree();

        return $this->render('index', ['permissions' => $permissions]);
    }

    public function actionView($id)
    {
        $permission = $this->permissionsService->getByName($id);
        return $this->render('view', ['permission' => $permission]);
    }

    public function actionCreate(string $parent = null)
    {
        if (\Yii::$app->request->isPost) {
            $name = \Yii::$app->request->post('name', '');
            $parent = \Yii::$app->request->get('parent', '');
            $this->permissionsService->add($name, $parent);
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => new Permission()]);
    }

    public function actionUpdate(string $id)
    {
        throw new ForbiddenHttpException('Данное действие запрещено');
        if (\Yii::$app->request->isPost) {
            $role = $this->permissionsService->getByName($id);
            $newName = \Yii::$app->request->post('name');
            $this->permissionsService->update($id, $newName);

            return $this->redirect(['index']);
        }
        $permission = $this->permissionsService->getByName($id);
        return $this->render('update', ['model' => $permission]);
    }

    public function actionDelete($id)
    {
        $this->permissionsService->remove($id);

        return $this->redirect(['index']);
    }

    public function actionAttachToRole(string $role)
    {
        if (\Yii::$app->request->isPost) {
            $role = $this->rolesService->getByName($role);
            $permission = \Yii::$app->request->post('permission');
            $this->permissionsService->attachToRole($role, $permission);

            return $this->redirect(['/rbac/roles/view', 'id' => $role->name]);
        }
        $permissions = $this->permissionsService->all();
        return $this->render('attach', ['permissions' => $permissions, 'role' => $role]);
    }

    public function actionDetachFromRole(string $role, string $permission)
    {
        if (\Yii::$app->request->isPost) {
            $role = $this->rolesService->getByName($role);
            $permission = $this->permissionsService->getByName($permission);
            $this->permissionsService->detachFromRole($role, $permission);

            return $this->redirect(['/rbac/roles/view', 'id' => $role->name]);
        }
        $permissions = $this->permissionsService->all();
        return $this->render('attach', ['permissions' => $permissions, 'role' => $role]);
    }
}
