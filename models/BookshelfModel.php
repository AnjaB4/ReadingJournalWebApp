<?php

namespace app\models;

use app\core\BaseModel;

class BookshelfModel extends BaseModel
{
    public function getFinishedBooks(int $userId) : array
    {
        $this->id_user = $userId;

        $query = "select distinct b.id, b.title, b.author, b.cover_image
                    from reading_log rl
                    inner join books b 
                        on rl.id_book = b.id
                    where rl.id_user = $this->id_user and rl.status = 'completed'
                  order by rl.end_date desc";
        
        $result = $this->con->query($query);

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