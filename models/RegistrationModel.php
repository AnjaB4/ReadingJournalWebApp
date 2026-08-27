<?php

namespace app\models;

use app\core\BaseModel;

class RegistrationModel extends BaseModel
{
    public int $id;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';

    public function tableName(): string
    {
        return 'users';
    }

    public function readColumns(): array
    {
        return ['id', 'first_name', 'last_name', 'email', 'password'];
    }

    public function editColumns(): array
    {
        return ['first_name', 'last_name', 'email', 'password'];
    }

    public function validationRules(): array
    {
        return [
            "first_name" => [self::RULE_REQUIRED],
            "last_name" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            "password" => [self::RULE_REQUIRED]
        ];
    }
}