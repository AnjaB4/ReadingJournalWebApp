<?php

namespace app\models;

use app\core\BaseModel;

class ReadingLogModel extends BaseModel
{
    public int $id;
    public ?int $id_user = null;
    public ?int $id_book = null;
    public string $status = 'tbr';
    public ?string $start_date = null; // datum kada korisnik pocinje citati
    public ?string $end_date = null;   // datum kada zavrsava citanje
    public ?string $updated_at = null;

    public function getLogData($userId, $sortBy = 'end_date', $order = 'DESC'): array
    {
        $query = "select rl.id, rl.status, rl.start_date, rl.end_date, 
                        u.id as id_user, u.first_name, 
                        b.title, b.author, b.cover_image, b.page_count
                    from reading_log rl 
                    left join users u 
                        on rl.id_user = u.id 
                    left join books b 
                        on rl.id_book = b.id 
                    where rl.id_user = $userId
                    order by $sortBy $order";
        //rl.end_date desc, rl.updated_at desc
        //order by rl.updated_at desc

        $dbResult = $this->con->query($query);

        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }
        return $resultArray;
    }

    public function tableName(): string
    {
        return 'reading_log';
    }

    public function readColumns(): array
    {
        return ["id", "id_user", "id_book", "status", "start_date", "end_date", "created_at", "updated_at"];
    }

    public function editColumns(): array
    {
        return ["id_user", "id_book", "status", "start_date", "end_date"];
    }

    public function validationRules(): array
    {
        // TODO: Implement validationRules() method.
        return [
            "status" => [self::RULE_REQUIRED],
            "end_date" => [self::RULE_DATE]
        ];
    }
}