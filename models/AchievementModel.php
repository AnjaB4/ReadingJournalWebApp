<?php

namespace app\models;

use app\core\BaseModel;

class AchievementModel extends BaseModel
{
    public int $id;
    public string $title = '';
    public string $description = '';
    public int $xp_reward = 0;
    public ?string $icon = null;

    public function tableName(): string
    {
        return 'achievements';
    }

    public function readColumns(): array
    {
        return ["id", "title", "description", "xp_reward", "icon"];
    }

    public function editColumns(): array
    {
        return ["title", "description", "xp_reward", "icon"];
    }

    public function validationRules(): array
    { 
        return [
            "title" => [self::RULE_REQUIRED],
            "description" => [self::RULE_REQUIRED],
            "xp_reward" => [self::RULE_REQUIRED, self::RULE_POSITIVE]

        ];

    }
}