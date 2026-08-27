<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\BookshelfModel;
//
use app\models\BookGenresModel;
use app\models\BookModel;
use app\models\GenreModel;

class BookshelfController extends BaseController
{
    public function showBookshelf()
    {
        $sessionUser = Application::$app->session->get('user');
        if (!$sessionUser) {
            Application::$app->session->set('errorNotification', 'You need to be logged in!');
            header("location:" . "/login");
            exit;
        }

        $userId = $sessionUser[0]['id_user'];

        $bsModel = new BookshelfModel();
        $bgModel = new BookGenresModel();

        $books = $bsModel->getFinishedBooks($userId);

        foreach ($books as &$book) {
            $book['genres'] = $bgModel->getGenresOfBook($book['id']);
        }

        $data = [
            'books' => $books
        ];

        $this->view->render('myBookshelf', 'main', $data);

    }

    public function accessRole(): array
    {
        return ['User', 'Librarian']; // moze da pristupi User i Librarian
    }

}