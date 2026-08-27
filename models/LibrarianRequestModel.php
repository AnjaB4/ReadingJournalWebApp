<?php

namespace app\models;

use app\core\BaseModel;

class LibrarianRequestModel extends BaseModel
{
    public int $id;
    public int $id_user;
    public string $status = 'pending';
    public string $created_at;

    public function tableName(): string
    {
        return 'librarian_requests';
    }

    public function readColumns(): array
    {
        return ['id', 'id_user', 'status', 'created_at'];
    }

    public function editColumns(): array
    {
        return ['id_user', 'status'];
    }

    public function validationRules(): array
    {
        return [
            'id_user' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED]
        ];
    }
}
