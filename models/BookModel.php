<?php


namespace app\models;

use app\core\BaseModel;

class BookModel extends BaseModel
{
    public int $id;
    public string $title = '';
    public string $synopsis = '';
    public string $author = '';
    public ?int $page_count = null;
    public ?string $cover_image = null;
    public array $genres = [];

    public function tableName(): string
    {
        return 'books';
    }

    public function readColumns(): array
    {
        return ["id", "title", "synopsis", "author", "page_count", "cover_image"];
    }

    public function editColumns(): array
    {
        return ["title", "synopsis", "author", "page_count"];
    }

    public function validationRules(): array
    { //vracamo niz gde ce svaki nas property imati niz validacija
        return [
            "title" => [self::RULE_REQUIRED, self::RULE_NO_QUOTES],
            "synopsis" => [self::RULE_NO_QUOTES],
            "author" => [self::RULE_REQUIRED, self::RULE_NO_QUOTES],
            "page_count" => [self::RULE_REQUIRED, self::RULE_POSITIVE] //BITAN REDOSLED

        ];

    }

}