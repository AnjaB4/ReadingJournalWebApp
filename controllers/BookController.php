<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\BookGenresModel;
use app\models\BookModel;
use app\models\GenreModel;

class BookController extends BaseController
{
    public function books()
    {
        $model = new BookModel();

        $search = trim($_GET['search'] ?? '');

        if ($search !== ''){
            $result = $model->search($search);
        } else {
            $result = $model->all("");
        }

        $bgModel = new BookGenresModel();

        // dodaj žanrove svakoj knjizi //////
        foreach ($result as &$book) {                                
            $book['genres'] = $bgModel->getGenresOfBook($book['id']);
        }                                                            
        

        $this->view->render('books', 'main', $result);
    }
    public function update()
    {
        $model = new BookModel();

        $model->mapData($_GET);

        $model->one("where id = $model->id");

        $bgModel = new BookGenresModel();
        $model->genres = $bgModel->getGenresOfBook($model->id);

        $genreModel = new GenreModel();
        $genres = $genreModel->allGenres();

        $this->view->render('updateBook', 'main', [
            'book' => $model,
            'genres' => $genres
        ]);
    }

    public function processUpdate()
    {
        $model = new BookModel();

        // rešavamo problem sa praznim stringom za int property
        $this->checkPageProperty();

        $model->mapData($_POST);

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Update unsuccessful!');

            $genreModel = new GenreModel();
            $genres = $genreModel->allGenres();
            $this->view->render('createBook', 'main', [
                'book' => $model,
                'genres' => $genres
            ]);

            $this->view->render('updateBook', 'main', $model);
            exit;
        }

        $model->update("where id = $model->id");

        $bgModel = new BookGenresModel();
        $bgModel->removeGenresForBook($model->id);

        if (isset($_POST['genres'])) {
            foreach ($_POST['genres'] as $genreId) {
                $bgModel->addGenreToBook($model->id, (int)$genreId);
            }
        }

        Application::$app->session->set('successNotification', 'Successfully updated!');

        header("location:" . "/books");
    }

    public function create()   //samo renderuje FORMU
    {
        $model = new BookModel();

        $genreModel = new GenreModel();

        $genres = $genreModel->allGenres();

        $this->view->render('createBook', 'main', [
            'book' => $model,
            'genres' => $genres
        ]);
    }
    public function processCreate() //ZAPRAVO VRSI Create
    {
        $model = new BookModel();

        /*
         Kad submituješ formu i polje za broj strana (page_count) ostane prazno, HTML input šalje prazan string "", a ne null.
PHP onda u mapData() pokušava da upiše taj prazan string u $page_count, ali pošto je ?int,
        PHP baca grešku jer "" nije ni int ni null.
         */
        
        $this->checkPageProperty();

        $model->mapData($_POST); //saljemo podatke kroz FORMU

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Not created!');

            $genreModel = new GenreModel();
            $genres = $genreModel->allGenres();
            $this->view->render('createBook', 'main', [
                'book' => $model,
                'genres' => $genres
            ]);
            exit;
        }

        $model->insert();

        $bookId = $model->con->insert_id;

        if (isset($_POST['genres'])) {
            $bgModel = new BookGenresModel();
            foreach ($_POST['genres'] as $genreId) {
                $bgModel->addGenreToBook($bookId, (int)$genreId);
            }
        }

        Application::$app->session->set('successNotification', 'Successfully created!');

        header("location:" . "/books"); //redirectovanje
    }


    public function delete()
    {
        $model = new BookModel();

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $success = $model->delete("where id = $id");

            if ($success) {
                Application::$app->session->set('successNotification', 'Book deleted successfully!');
            } else {
                Application::$app->session->set('errorNotification', 'Delete failed!');
            }
        }

        header("Location:" . "/books"); // redirect nazad na listu knjiga
        exit;
    }

    public function accessRole(): array
    {
        return ['User', 'Librarian']; // moze da pristupi User i Librarian
    }

    /**
     * @return void
     */
    public function checkPageProperty(): void
    {
        if (isset($_POST['page_count']) && $_POST['page_count'] === '') {
            $_POST['page_count'] = null;
        }
    }

}