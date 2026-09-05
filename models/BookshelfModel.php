<?php

namespace app\models;

use app\core\BaseModel;

class BookshelfModel extends BaseModel
{
    public function getBooksByStatus(int $userId, string $status) : array
    {

        $query = "select distinct b.id, b.title, b.author, b.cover_image
                    from reading_log rl
                    inner join books b 
                        on rl.id_book = b.id
                    where rl.id_user = ? and rl.status = ?
                  order by rl.end_date desc";
        
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("is", $userId, $status);
        $stmt->execute();
        
        $result = $stmt->get_result();

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        return $books;
      
    }


    public function tableName(): string
    {
        return '';
    }
    public function readColumns(): array 
    {
        return [];
    }
    public function editColumns(): array 
    {
        return [];
    }
    public function validationRules()
    {
    
    }

}