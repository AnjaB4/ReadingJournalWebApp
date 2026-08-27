<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\ReportModel;

class LibrarianReportController extends BaseController
{
    public function librarianReports()
    {
        $this->view->render('librarianReports', 'main', null);
    }

    public function getBooksPerUser()
    {
        $model = new ReportModel();
        //da bi bio dinamican
        $model->mapData($_GET);
        $model->getBooksPerUser();

    }

    public function getBooksPerGenre()
    {
        $model = new ReportModel();
        $model->mapData($_GET);
        $model->getBooksPerGenre();

    }

    public function accessRole(): array
    {
        return ['Librarian'];
    }
}