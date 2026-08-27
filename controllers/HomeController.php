<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\LibrarianRequestModel;
use app\models\UserModel;
use app\models\StatisticsModel;

class HomeController extends BaseController
{
    public function home()
    {
        $sessionUser = Application::$app->session->get('user');
//        var_dump($sessionUser);exit;
        $userId = $sessionUser[0]['id_user'];

        // XP calculation
        $stats = new StatisticsModel();
        $stats->id_user = $userId;
        $xp = $stats->calculateXP();

        $level = floor(sqrt($xp / 50)) + 1;

        $xpPrev = 50* pow($level - 1, 2);
        $xpNext = 50 * pow($level, 2);

        $progress = (($xp - $xpPrev) / ($xpNext - $xpPrev)) *100; // progres ka sledecem nivou
        $xpToNextLevel = $xpNext - $xp; // koliko XP treba do sledeceg nivoa
        //

        $model = new UserModel();
        $model->one("where id = $userId");
        $profile_picture = $model->profile_picture; // dobijam ime slike pfp

        $reqModel = new LibrarianRequestModel();
        $lastReq = $reqModel->all("where id_user = $userId order by created_at desc limit 1");
        $librarianRequestStatus = $lastReq[0]['status'] ?? 'none';

        $this->view->render('home', 'main', [
            'profile_picture' => $profile_picture,
            'librarianRequestStatus' => $librarianRequestStatus,
            'xp' => $xp,
            'level' => $level,
            'progress' => $progress,
            'xpToNextLevel' => $xpToNextLevel
            ]);
    }

    public function about()
    {
        $this->view->render('home', 'main', null);
    }

    public function requestLibrarian()
    {
        $sessionUser = Application::$app->session->get('user');
        $userId = $sessionUser[0]['id_user'];

        $reqModel = new LibrarianRequestModel();

        $existing = $reqModel->all("where id_user = $userId and status = 'pending'");
        if (!empty($existing)) {
            Application::$app->session->set('errorNotification', 'You already have a pending request.');
            header("location: " . "/home");
            exit;
        }

        $reqModel->id_user = $userId;
        $reqModel->status = 'pending';
        $reqModel->insert();

        Application::$app->session->set('successNotification', 'Request sent to librarians.');
        header("location: " . "/home");
    }

    public function accessRole(): array
    {
        return ['User', 'Librarian'];
    }

}