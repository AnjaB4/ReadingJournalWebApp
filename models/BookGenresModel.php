<?php

namespace app\models;

use app\core\BaseModel;

class BookGenresModel extends BaseModel
{
    public int $id;
    public int $id_book;
    public int $id_genre;


    public function getGenresOfBook(int $bookId): array //zanrovi za odredjenu knjigu
    {
        $query = "
            select g.id, g.name, g.color_class
            from genres g
            join book_genres bg on g.id = bg.id_genre
            where bg.id_book = $bookId
        ";

        $dbResult = $this->con->query($query);

        $genres = [];
        while ($row = $dbResult->fetch_assoc()) {
            $genres[] = $row;
        }
        return $genres;
    }

    public function addGenreToBook(int $bookId, int $genreId) //dodaj zanr knjizi
    {
        $query = "insert into book_genres (id_book, id_genre) values ($bookId, $genreId)";
        return $this->con->query($query);

    }

    public function removeGenresForBook(int $bookId)
    {
        $query = "delete from book_genres where id_book = $bookId";
        return $this->con->query($query);
    }

    public function tableName(): string
    {
        return 'book_genres';
    }

    public function readColumns(): array
    {
        return ['id', 'id_book', 'id_genre'];
    }

    public function editColumns(): array
    {
        return ['id_book', 'id_genre'];
    }

    public function validationRules(): array
    {
        return [];
    }
}