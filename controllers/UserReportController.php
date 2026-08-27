<?php

namespace app\controllers;

use app\core\BaseController;
use app\models\ReportModel;

class UserReportController extends BaseController
{
    public function myReports()
    {
        $this->view->render('myReports', 'main', null);
    }

    public function getNumberOfBooksPerMonth()
    {
        $model = new ReportModel();
        $model->mapData($_GET);
        $model->getNumberOfBooksPerMonth();

    }

    public function getNumberOfPagesPerMonth()
    {
        $model = new ReportModel();
        $model->getNumberOfPagesPerMonth();

    }

    public function getNumberOfGenres()
    {
        $model = new ReportModel();
        $model->getNumberOfGenres();

    }

    public function getNumberOfBooksPerStatus()
    {
        $model = new ReportModel();
        $model->getNumberOfBooksPerStatus();

    }

    public function getNumberOfBooksPerPageCount() //koliko knjiga kojih velicina(>300, <300 itd)
    {
        $model = new ReportModel();
        $model->getNumberOfBooksPerPageCount();

    }


    public function accessRole(): array
    {
        return ['User', 'Librarian'];
    }
}