<?php

namespace app\models;

use app\core\BaseModel;

class RoleModel extends BaseModel
{
    public int $id;
    public string $name;

    public function tableName(): string
    {
        return 'roles';
    }

    public function readColumns(): array
    {
        return ['id', 'name'];
    }

    public function editColumns(): array
    {
        return ['name'];
    }

    public function validationRules(): array
    {
        return [
            "name" => [self::RULE_REQUIRED]
        ];
    }
}