<?php

namespace app\models;

use app\core\BaseModel;

class GenreModel extends BaseModel
{
    public int $id;
    public string $name = '';
    public string $color_class;

    public function allGenres(): array
    {
        return $this->all("");
    }

    public function tableName(): string
    {
        return 'genres';
    }

    public function readColumns(): array
    {
        return ['id', 'name', 'color_class'];
    }

    public function editColumns(): array
    {
        return ['name', 'color_class'];
    }

    public function validationRules(): array
    {
        return ["name" => [self::RULE_REQUIRED]];
    }
}