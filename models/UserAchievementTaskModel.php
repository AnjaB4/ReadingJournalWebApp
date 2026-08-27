<?php

namespace app\models;

use app\core\BaseModel;

class UserAchievementTaskModel extends BaseModel
{
    public int $id;
    public int $id_user;
    public int $id_task;
    public int $id_book;

    public function tableName(): string
    {
        return 'user_achievement_tasks';
    }

    public function readColumns(): array
    {
        return ["id", "id_user", "id_task", "id_book"];
    }

    public function editColumns(): array
    {
        return ["id_user", "id_task", "id_book"];
    }

    public function validationRules(): array
    {
        return [];
    }

    // dohvati odredjeni task
    public function getTaskForUser(int $userId, int $taskId): ?array
    {
        $query = "select *
                from user_achievement_tasks
                where id_user = $userId
                    and id_task = $taskId";

        $result = $this->con->query($query);

        return $result->fetch_assoc() ?: null;
    }

    // dohvati SVE taskove usera iz baze za view
    public function getTasksForUser(int $userId): array
    {
        return $this->all("where id_user = $userId");
    }

    public function saveTaskBook(int $userId, int $taskId, int $bookId): void
    {
        $existing = $this->getTaskForUser($userId, $taskId);

        if ($existing) {

            // user menja knjigu za vec završen task
            $query = "update user_achievement_tasks
                    set id_book = $bookId
                    where id_user = $userId
                        and id_task = $taskId";

        } else {

            // prvi put bira knjigu za ovaj task
            $query = "insert into user_achievement_tasks
                        (id_user, id_task, id_book)
                    values
                        ($userId, $taskId, $bookId)";
        }

        $this->con->query($query);
    }

    // DELETE odabir
    public function deleteTaskBook(int $userId, int $taskId): void
    {
        $query = "delete from user_achievement_tasks
                    where id_user = $userId
                    and id_task = $taskId";
        $this->con->query($query);
    }

    // Da li su svi taskovi zavrseni
    public function isAchievementComplete(int $userId, int $achievementId): bool
    {
        $query = "
            select count(*) as total_tasks,
                count(uat.id_task) as completed_tasks
            from tasks t
            left join user_achievement_tasks uat
                on t.id = uat.id_task
                and uat.id_user = $userId
            where t.id_achievement = $achievementId
        ";

        $result = $this->con->query($query);
        $row = $result->fetch_assoc();

        return $row['total_tasks'] > 0
            && $row['total_tasks'] == $row['completed_tasks'];
    }
}