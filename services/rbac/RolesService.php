<?php

namespace common\services\rbac;

use common\models\User;
use Webmozart\Assert\Assert;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;

class RolesService
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * RoleService constructor.
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function add(string $name, string $parent = null): void
    {
        $role = $this->manager->createRole($name);
        Assert::true($this->manager->add($role));
        if ($parent) {
            $parent = $this->getByName($parent);
            Assert::true($this->manager->canAddChild($parent, $role));
            Assert::true($this->manager->addChild($parent, $role));
        }
    }

    public function update(string $name, string $newName): void
    {
        $role = $this->manager->createRole($newName);
        Assert::true($this->manager->update($name, $role));
    }

    public function remove(string $name): void
    {
        Assert::isEmpty(User::DEFAULT_ROLES[$name] ?? null, 'Удаление данной роли запрещено');
        $role = $this->getByName($name);
        Assert::true($this->manager->remove($role));
    }

    /**
     * @param int $userId
     * @param string $role
     * @throws \Exception
     */
    public function attachRole(int $userId, string $role): void
    {
        $userRole = $this->manager->getRole($role);
        $this->manager->revokeAll($userId);
        $this->manager->assign($userRole, $userId);
    }

    /**
     * @return Role[]
     */
    public function all(): array
    {
        return $this->manager->getRoles();
    }

    /**
     * Строит дерево ролей
     * @return Role[]
     */
    public function tree(): array
    {
        $roles = $this->manager->getRoles();
        foreach ($roles as $name => $role) {
            $this->setChildren($role, $roles);
        }
        return $roles;
    }

    /**
     * Рекурсивно добавляет к роли её детей
     * @param Role $role
     * @param array $roles
     * @return Role
     */
    public function setChildren(Role $role, array &$roles): Role
    {
        $children = $this->manager->getChildren($role->name);
        $childRoles = array_filter($children, function (Item $item) {
            return $item instanceof Role;
        });
        foreach ($childRoles as $name => $childRole) {
            if (!empty($roles[$name])) {
                unset($roles[$name]);
            }
            $childRoles[$name] = $this->setChildren($childRole, $roles);
        }
        $role->data['roles'] = $childRoles;
        $role->data['permissions'] = $this->manager->getPermissionsByRole($role->name);

        return $role;
    }

    /**
     * @param string $name
     * @return Role[]
     */
    public function getChildren(string $name): array
    {
        return $this->manager->getChildren($name);
    }

    /**
     * @param int|null $id
     * @return Role
     */
    public function getRoleByUserId(int $id): Role
    {
        $roles = $this->manager->getRolesByUser($id);
        if (!$roles) {
            throw new \DomainException('У пользователя отсутствует роль');
        }
        return current($roles);
    }

    public function findByName(string $name): ?Role
    {
        return $this->manager->getRole($name);
    }

    public function getByName(string $name): Role
    {
        Assert::notNull($role = $this->manager->getRole($name));
        return $role;
    }

    /**
     * Проверяет достаточная ли у пользователя роль
     * @param string $role роль для проверки
     * @param int $id user id
     * @return bool
     */
    public function checkRole(string $role, int $id): bool
    {
        $userRole = $this->getRoleByUserId($id);
        $roles = $this->manager->getChildRoles($userRole->name);

        return ArrayHelper::isIn($role, array_keys($roles));
    }

    public function isUserRole(int $id): bool
    {
        $userRole = $this->getRoleByUserId($id);

        return $userRole->name === User::ROLE_USER;
    }

    public function getRoleByUser($userId): ?Role
    {
        $roles = $this->manager->getRolesByUser($userId);
        return reset($roles) ?: null;
    }

    public function assign(string $name, int $userId): Assignment
    {
        $role = $this->getByName($name);
        $this->manager->revokeAll($userId);
        return $this->manager->assign($role, $userId);
    }

    /**
     * @param string $name
     * @return Permission[]
     */
    public function getPermissionsByRole(string $name): array
    {
        return $this->manager->getPermissionsByRole($name);
    }
}