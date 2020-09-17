<?php

namespace backend\controllers\rbac;

use backend\components\Controller;
use common\services\rbac\PermissionsService;
use common\services\rbac\RolesService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Role;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class RolesController extends Controller
{
    /**
     * @var RolesService
     */
    private $rolesService;
    /**
     * @var PermissionsService
     */
    private PermissionsService $permissionsService;

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
        $roles = $this->rolesService->tree();

        return $this->render('index', ['roles' => $roles]);
    }

    public function actionView($id)
    {
        $role = $this->rolesService->getByName($id);
        $permissions = $this->permissionsService->tree($id);
//        dd($permissions);
        return $this->render('view', ['role' => $role, 'permissions' => $permissions]);
    }

    public function actionCreate(string $parent = null)
    {
        if (\Yii::$app->request->isPost) {
            $name = \Yii::$app->request->post('name', '');
            $parent = \Yii::$app->request->get('parent', '');
            $this->rolesService->add($name, $parent);
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => new Role()]);
    }

    public function actionUpdate(string $id)
    {
        throw new ForbiddenHttpException('Данное действие запрещено');
        if (\Yii::$app->request->isPost) {
            $role = $this->rolesService->getByName($id);
            $newName = \Yii::$app->request->post('name');
            $this->rolesService->update($id, $newName);

            return $this->redirect(['index']);
        }
        $role = $this->rolesService->getByName($id);
        return $this->render('update', ['model' => $role]);
    }

    public function actionDelete($id)
    {
        $this->rolesService->remove($id);

        return $this->redirect(['index']);
    }
}
