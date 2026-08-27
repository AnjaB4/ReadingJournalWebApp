<?php

namespace app\models;

use app\core\BaseModel;

class UserRoleModel extends BaseModel
{
    public int $id;
    public int $id_user;
    public int $id_role;

    public function tableName(): string
    {
        return 'user_roles';
    }

    public function readColumns(): array
    {
        return ['id', 'id_user', 'id_role'];
    }

    public function editColumns(): array
    {
        return ['id_user', 'id_role'];
    }

    public function validationRules(): array
    {
        return [
            "id_user" => [self::RULE_REQUIRED],
            "id_role" => [self::RULE_REQUIRED]
        ];
    }
}
