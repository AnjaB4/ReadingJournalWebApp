<?php

namespace app\services;

use app\models\UserAchievementModel;
use app\models\UserAchievementTaskModel;
use app\models\TaskModel;

class AchievementService
{
    private UserAchievementModel $userAchievementModel;
    private UserAchievementTaskModel $userAchievementTaskModel;
    private TaskModel $taskModel;

    // Konstruktor
    public function __construct(UserAchievementModel $userAchievementModel, UserAchievementTaskModel $userAchievementTaskModel, TaskModel $taskModel) 
    {
        $this->userAchievementModel = $userAchievementModel;
        $this->userAchievementTaskModel = $userAchievementTaskModel;
        $this->taskModel = $taskModel;
    }


    // SINHRONIZACIJA stanja ach. bice pozvana kad god se radi nesto sa taskovima ili ach.
    public function syncAchievement(int $userId, int $achievementId): void
    {
        // da li je ach. zavrsen? poziva metodu iz modela
        $isComplete = $this->userAchievementTaskModel->isAchievementComplete($userId, $achievementId);

        //moramo proveriti da li je ach. prethodno bio ikad osvojen (zbog XP dodele)
        // da li postoji red u medjutabeli? pozovi model koji obavlja odg query
        $existingAchievement = $this->userAchievementModel->getAchievementForUser($userId, $achievementId);


        // INSERT
        //ako je ach. gotov I nije vec upisan u tabelu (existing == null):
        if ($isComplete && !$existingAchievement) {
            $this->userAchievementModel->addToUser(
                $userId,
                $achievementId
            );

        }

        // DELETE
        //ako ach. NIJE gotov I postoji vec u tabeli:
        // za slucaj kada se taskovi koji su doveli do ispunjenja
        // obrisu, time korisnik treba da izgubi prethodno dodeljen ach.
        if (!$isComplete && $existingAchievement) {
            $this->userAchievementModel->delete(
                "where id_user = $userId
                and id_achievement = $achievementId"
            );
        }

    }

    // Za kada se obrise reading log a knjiga iz tog loga bila je dodeljena tasku
    public function handleBookRemoval(int $userId, int $bookId): void
    {
        $tasks = $this->userAchievementTaskModel->getTasksForBook(
            $userId,                      // pronadji taskove za knjigu koja se brise
            $bookId
        );

        $achievementIds = [];

        foreach ($tasks as $task) { // nadji ach. kojima ti taskovi pripadaju
            $achievement = $this->taskModel->getAchievementForTask(
                (int)$task['id_task']
            );

            if ($achievement) {
                $achievementIds[] = (int)$achievement['id_achievement'];
            }
        }

        // ne ponavljamo logiku za duplikate, npr. ako je vise taskova u istom ach.
        // uklanjamo duplikate
        $achievementIds = array_unique($achievementIds);

        // prvo obrisi taskove koji koriste tu knjigu
        $this->userAchievementTaskModel->deleteCompletionOfTasksForBook($userId, $bookId);


        // za svaki od ach. kojima obrisani taskovi pripadaju --> sync --> ukloni za usera
        foreach ($achievementIds as $achievementId) {
            $this->syncAchievement($userId, $achievementId);
        }
    }

    
}