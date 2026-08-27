<?php

namespace app\models;

use app\core\BaseModel;

class BookAuthorModel extends BaseModel
{
    public int $id;
    public int $id_book;
    public int $id_author;

    public function tableName(): string
    {
        return 'book_authors';
    }

    public function readColumns(): array
    {
        return ["id", "id_book", "id_author"];
    }

    public function editColumns(): array
    {
        return ["id_book", "id_author"];
    }


    // dohvati sve autore jedne knjige
    public function getAuthorsOfBook(int $bookId): array // autori za odredjenu knjigu
    {
        $query = "
            select a.id, a.name
            from authors a
            join book_authors ba on a.id = ba.id_author
            where ba.id_book = $bookId
        ";

        $dbResult = $this->con->query($query);

        $authors = [];

        while ($row = $dbResult->fetch_assoc()) {
            $authors[] = $row;
        }

        return $authors;
    }

    // dodaj autora knjizi
    public function addAuthorToBook(int $bookId, int $authorId) // dodaj autora knjizi
    {
        $query = "insert into book_authors (id_book, id_author) values ($bookId, $authorId)";

        return $this->con->query($query);
    }

    // izbrisi autora sa knjige
    public function removeAuthorsForBook(int $bookId)
    {
        $query = "delete from book_authors where id_book = $bookId";

        return $this->con->query($query);
    }

    public function validationRules(): array
    { //vracamo niz gde ce svaki nas property imati niz validacija
        return [
            "id_book" => [self::RULE_REQUIRED],
            "id_author" => [self::RULE_REQUIRED]

        ];

    }


}