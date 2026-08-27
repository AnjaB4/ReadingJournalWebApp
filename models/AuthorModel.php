<?php

namespace app\models;

use app\core\BaseModel;

class AuthorModel extends BaseModel
{
    public int $id;
    public string $name = '';

    public function tableName(): string
    {
        return 'authors';
    }

    public function readColumns(): array
    {
        return ["id", "name"];
    }

    public function editColumns(): array
    {
        return ["name"];
    }

    // TODO: IZMENI LOGIKU AUTORA

    // dovuci autora po imenu
    public function getAuthorByName(string $name): ?array
    {
        $name = trim($name);

        $query = "select id, name
                from authors
                where name = '$name'
                limit 1";

        $dbResult = $this->con->query($query);

        return $dbResult->fetch_assoc() ?: null;
    }


    public function validationRules(): array
    { //vracamo niz gde ce svaki nas property imati niz validacija
        return [
            "name" => [self::RULE_REQUIRED]

        ];

    }


}