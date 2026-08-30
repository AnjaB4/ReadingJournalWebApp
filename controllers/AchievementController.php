<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\AchievementModel;
use app\models\TaskModel;
use app\models\BookshelfModel;
use app\models\UserAchievementTaskModel;
use app\models\UserAchievementModel;
use app\services\AchievementService;

class AchievementController extends BaseController
{
    // prikaz svih achievements
    public function achievements()
    {
        $sessionUser = Application::$app->session->get('user');
        if (!$sessionUser) {
            header("location:" . "/login");
            exit;
        }
        $userId = $sessionUser[0]['id_user'];

        
        $achievementModel = new AchievementModel();
        $achievements = $achievementModel->all("");
        
        $taskModel = new TaskModel();

        // zbog odabira procitanih knjige za achievement tasks
        $bsModel = new BookshelfModel();
        $books = $bsModel->getFinishedBooks($userId);

        // vec sacuvani taskovi trenutnog usera
        $userAchievementTaskModel = new UserAchievementTaskModel();
       
        $userTasks = $userAchievementTaskModel->all(
            "where id_user = $userId"
        );

    
        foreach ($achievements as &$achievement) {
            $achievement['tasks'] = $taskModel->all(
                "where id_achievement = " . $achievement['id']
            );
        }

        $data = [
            'achievements' => $achievements,
            'books' => $books,
            'userTasks' => $userTasks
        ];
        

        $this->view->render('achievements', 'main', $data);
    }

    // SACUVAJ odabir knjige za task
    public function saveAchievementTask()
    {
        $sessionUser = Application::$app->session->get('user');

        if (!$sessionUser) {
            header("location:" . "/login");
            exit;
        }

        $userId = (int)$sessionUser[0]['id_user'];

        $model = new UserAchievementTaskModel();
        $taskModel = new TaskModel();

        // napravi service
        $achievementService = new AchievementService(
            new UserAchievementModel(),
            $model,
            $taskModel
        );

        foreach ($_POST as $task => $bookId) {

            // ako korisnik nije izabrao knjigu, preskoči task
            if ($bookId === '') {
                continue;
            }

            // iz "task_4" dobijamo 4
            $taskId = (int)str_replace('task_', '', $task);
            $bookId = (int)$bookId;

            // sacuvaj knjigu za task
            $model->saveTaskBook($userId, $taskId, $bookId);

            // pronadji achievement kojem ovaj task pripada
            $achievement = $taskModel->getAchievementForTask($taskId);

            // koristimo service da sinkujemo progress ach.
            if ($achievement) {
                
                $achievementId = $achievement['id_achievement'];

                $achievementService->syncAchievement(
                    $userId,
                    $achievementId
                );
            }
        }

        Application::$app->session->set(
            'successNotification',
            'Achievement tasks saved successfully!'
        );

        header("location:" . "/achievements");
        exit;
    }

    public function deleteAchievementTask()
    {
        $sessionUser = Application::$app->session->get('user');

        if (!$sessionUser) {
            header("location:" . "/login");
            exit;
        }

        $userId = (int)$sessionUser[0]['id_user'];
        $taskId = (int)($_POST['task_id'] ?? 0);


        $model = new UserAchievementTaskModel();
        $taskModel = new TaskModel();

        // kreiraj servis
        $achievementService = new AchievementService(
            new UserAchievementModel(),
            $model,
            $taskModel
        );

        if ($taskId > 0) {
            
            $achievement = $taskModel->getAchievementForTask($taskId);

            if($achievement) {
                $achievementId = $achievement['id_achievement'];

                $model->deleteTaskBook($userId, $taskId);

                $achievementService->syncAchievement(
                    $userId,
                    $achievementId
                );
            }
        }

        Application::$app->session->set(
            'successNotification',
            'Task reset successfully!'
        );

        header("location:" . "/achievements");
        exit;
    }
    

    public function accessRole(): array
    {
        return ['User', 'Librarian']; // moze da pristupi User i Librarian
    }
}