<?php

namespace app\models;

use app\core\BaseModel;

class UserAchievementModel extends BaseModel
{
    public int $id;
    public int $id_user;
    public int $id_achievement;
    public ?string $unlocked_at = null;

    public function tableName(): string
    {
        return 'user_achievements';
    }

    public function readColumns(): array
    {
        return ["id", "id_user", "id_achievement", "unlocked_at"];
    }

    public function editColumns(): array
    {
        return ["id_user", "id_achievement"];
    }


    // SELECT
    // za proveru koji achievemneti su vec osvojeni
    public function getAchievementForUser(int $userId, int $achievementId): ?array
    {
        $query = "select *
                from user_achievements
                where id_user = $userId
                and id_achievement = $achievementId";

        $dbResult = $this->con->query($query);

        return $dbResult->fetch_assoc() ?: null;
    }

    // INSERT
    // ako je achievement ispunjen, dodeli ga tom korisniku; Controller ce kasnije da racuna XP
    public function addToUser(int $userId, int $achievementId)
    {
        $query = "insert into user_achievements
                    (id_user, id_achievement)
                values
                    ($userId, $achievementId)";

        return $this->con->query($query);
    }
    

    // VALIDACIJA
    public function validationRules(): array
    {
        return [];
    }
}