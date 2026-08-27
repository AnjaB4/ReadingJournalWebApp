<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\BookModel;
use app\models\ReadingLogModel;

class ReadingLogController extends BaseController
{
    // prikaz loga za trenutno ulogovanog korisnika
    public function myLog()
    {
        $sessionUser = Application::$app->session->get('user');
        if (!$sessionUser) {
            header("location:" . "/login");
            exit;
        }
        $userId = $sessionUser[0]['id_user']; // id prvog usera (uvek postoji User role)

        //
        $sortBy = $_GET['sortBy'] ?? 'end_date';
        $order = strtoupper($_GET['order'] ?? 'DESC');
        
        $validSortColumns = ['title', 'author', 'status', 'start_date', 'end_date', 'page_count'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'end_date';
        }


        $readingLogModel = new ReadingLogModel();
        $result = $readingLogModel->getLogData($userId, $sortBy, $order);

        $this->view->render('readingLog', 'main', $result);
    }

    public function create()
    {
        $log = new ReadingLogModel();
        $log->status = 0; // default tbr

        $books = $this->getAllBooks();

        $this->view->render('createLog', 'main', [
            'params' => $log,
            'books' => $books
        ]);
    }
    public function processCreate()
    {
        $model = new ReadingLogModel();

        $model->mapData($_POST);

        // id trenutno ulogovanog usera
        $this->getCurrentUserId($model);

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Not created!');

            $books = $this->getAllBooks();

            $this->view->render('createLog', 'main', [
                'params' => $model,
                'books' => $books
            ]);
            exit;
        }

        $model->insert();

        Application::$app->session->set('successNotification', 'Successfully created!');

        header("location:" . "/readingLog"); //redirectovanje
    }

    public function update()
    {
        $model = new ReadingLogModel();

        $model->mapData($_GET);

        $model->one("where id = $model->id");

        // Dohvati sve knjige za dropdown
        $books = $this->getAllBooks();

        $this->view->render('updateLog', 'main', [
            'params' => $model,
            'books' => $books
        ]);
    }

    public function processUpdate()
    {
        $model = new ReadingLogModel();

        $model->mapData($_POST);

        $model->id_book = (int)$model->id_book;

        $this->getCurrentUserId($model);

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Update unsuccessful!');

            $books = $this->getAllBooks();

            $this->view->render('updateLog', 'main', [
                'params' => $model,
                'books' => $books
            ]);
            exit;
        }

        $model->update("where id = $model->id");

        Application::$app->session->set('successNotification', 'Successfully updated!');

        header("location:" . "/readingLog");
    }

    public function delete()
    {
        $model = new ReadingLogModel();

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $success = $model->delete("where id = $id");

            if ($success) {
                Application::$app->session->set('successNotification', 'Log deleted successfully!');
            } else {
                Application::$app->session->set('errorNotification', 'Delete failed!');
            }
        }

        header("Location:" . "/readingLog");
        exit;
    }

    private function getAllBooks(): array
    {
        $bookModel = new BookModel();
        return $bookModel->all("");
    }

    private function getCurrentUserId(&$model)
    {
        $sessionUser = Application::$app->session->get('user');
        if (!$sessionUser) {
            header("location:" . "/login");
            exit;
        }
        $model->id_user = (int)$sessionUser[0]['id_user'];
    }



    public function accessRole(): array
    {
        return ['User']; //samo za usere
    }
}