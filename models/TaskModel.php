<?php

namespace app\models;

use app\core\BaseModel;

class TaskModel extends BaseModel
{
    public int $id;
    public int $id_achievement;
    public string $title = '';
    public string $description = '';

    public function tableName(): string
    {
        return 'tasks';
    }

    public function readColumns(): array
    {
        return ["id", "id_achievement", "title", "description"];
    }

    public function editColumns(): array
    {
        return ["id_achievement", "title", "description"];
    }

    public function getTasksForAchievement($id_achievement): array
    {
        return $this->all("where id_achievement = $id_achievement");
    }

    // kojem achievement id-ju ovaj task pripada
    public function getAchievementForTask(int $taskId): ?array
    {
        $query = "select id_achievement
                from tasks
                where id = $taskId";

        $result = $this->con->query($query);

        return $result->fetch_assoc() ?: null;
    }

    public function validationRules(): array
    {
        return [
            "title" => [self::RULE_REQUIRED],
            "description" => [self::RULE_REQUIRED]

        ];
    }
}