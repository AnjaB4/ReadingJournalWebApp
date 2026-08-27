<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\BookModel;
use app\models\ReadingLogModel;
use app\models\StatisticsModel;

class StatisticsController extends BaseController
{

    public function getStats(){

        $sessionUser = Application::$app->session->get('user');
//        var_dump($sessionUser);
        if (!$sessionUser) {
            header("location:" . "/login");
            Application::$app->session->set('errorNotification', 'You need to be logged in!');
            exit;
        }
        $userId = $sessionUser[0]['id_user'];

        $stats = new StatisticsModel();
        $stats->id_user = $userId;

        $data = [
            'Books per month' => $stats->booksPerMonth(),
            'Pages per month' => $stats->pagesPerMonth(),
            'Top genres' => $stats->topGenres(),
            'Top authors' => $stats->topAuthors(),
            'Books per status' => $stats->booksPerStatus()

           // dodaj po potrebi koji data prikupljas i koju taj podatak fju poziva iz modela
        ];

        $this->view->render('statistics', 'main', $data);
    }

    public function accessRole(): array
    {
        return ['User'];
    }
}