<?php

namespace common\services\rbac;

use Webmozart\Assert\Assert;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;

class PermissionsService
{
    const USER_Permission = 'user';
    const AUTHOR_Permission = 'author';
    const MODERATOR_Permission = 'moderator';
    const ADMIN_Permission = 'admin';
    /**
     * @var ManagerInterface
     */
    private $manager;
    /** @var int User id */
    private $id;

    /**
     * PermissionService constructor.
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->id = \Yii::$app->user->id;
        $this->manager = $manager;
    }

    public function add(string $name, string $parent = null): void
    {
        $permission = $this->manager->createPermission($name);
        Assert::true($this->manager->add($permission));
        if ($parent) {
            $parent = $this->getByName($parent);
            Assert::true($this->manager->canAddChild($parent, $permission));
            Assert::true($this->manager->addChild($parent, $permission));
        }
    }

    public function update(string $name, string $newName): void
    {
        $permission = $this->manager->createPermission($newName);
        Assert::true($this->manager->update($name, $permission));
    }

    public function remove(string $name): void
    {
        $permission = $this->getByName($name);
        Assert::true($this->manager->remove($permission));
    }

    /**
     * @param int $userId
     * @param string $permission
     * @throws \Exception
     */
    public function attachPermission(int $userId, string $permission): void
    {
        $userPermission = $this->manager->getPermission($permission);
        $this->manager->revokeAll($userId);
        $this->manager->assign($userPermission, $userId);
    }

    /**
     * @return Permission[]
     */
    public function all(): array
    {
        return $this->manager->getPermissions();
    }

    /**
     * Строит дерево разрешений
     * @param string|null $roleName
     * @return Permission[]
     */
    public function tree(string $roleName = null): array
    {
        if ($roleName) {
            $permissions = $this->manager->getPermissionsByRole($roleName);
        } else {
            $permissions = $this->manager->getPermissions();
        }
        foreach ($permissions as $name => $permission) {
            $this->setChildren($permission, $permissions);
        }
        return $permissions;
    }

    /**
     * Рекурсивно добавляет к разрешению его детей
     * @param Permission $permission
     * @param Permission[] $permissions
     * @return Permission
     */
    public function setChildren(Permission $permission, array &$permissions): Permission
    {
        $children = $this->manager->getChildren($permission->name);
        $childPermissions = array_filter($children, function (Item $item) {
            return $item instanceof Permission;
        });
        foreach ($childPermissions as $name => $childPermission) {
            if (!empty($permissions[$name])) {
                unset($permissions[$name]);
            }
            $childPermissions[$name] = $this->setChildren($childPermission, $permissions);
        }
        $permission->data = $childPermissions;

        return $permission;
    }

    /**
     * @param string $name
     * @return Permission[]
     */
    public function getChildren(string $name): array
    {
        return $this->manager->getChildren($name);
    }

    /**
     * @param int|null $id
     * @return Permission
     */
    public function getPermissionByUserId(int $id = null): Permission
    {
        $id = $this->getId($id);
        $permissions = $this->manager->getPermissionsByUser($id);
        if (!$permissions) {
            throw new \DomainException('У пользователя отсутствует роль');
        }
        return current($permissions);
    }

    public function findByName(string $name): ?Permission
    {
        return $this->manager->getPermission($name);
    }

    public function getByName(string $name): Permission
    {
        Assert::notNull($permission = $this->manager->getPermission($name));
        return $permission;
    }

    /**
     * Проверяет достаточная ли у пользователя роль
     * @param string $permission роль для проверки
     * @param int $id user id
     * @return bool
     */
    public function checkPermission(string $permission, int $id = null): bool
    {
        $id = $this->getId($id);
        $userPermission = $this->getPermissionByUserId($id);
        $permissions = $this->manager->getChildPermissions($userPermission->name);

        return ArrayHelper::isIn($permission, array_keys($permissions));
    }

    public function isUserPermission(int $id = null): bool
    {
        $id = $this->getId($id);
        $userPermission = $this->getPermissionByUserId($this->getId($id));

        return $userPermission->name === self::USER_Permission;
    }

    private function getId(int $id = null)
    {
        return $id ?: $this->id;
    }

    public function attachToRole(Role $role, string $permission): void
    {
        $permission = $this->getByName($permission);
        Assert::true($this->manager->canAddChild($role, $permission));
        Assert::true($this->manager->addChild($role, $permission));
    }

    public function detachFromRole(Role $role, Permission $permission)
    {
        Assert::true($this->manager->removeChild($role, $permission));
    }
}