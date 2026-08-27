<?php

namespace app\models;

use app\core\BaseModel;

class StatisticsModel extends BaseModel
{
    public ?int $id_user = null;

    public function booksPerMonth(): array
    {
        $query = "select
                    year(rl.end_date) as year,
                    month(rl.end_date) as month,
                    monthname(rl.end_date) AS month_name, 
                    count(*) as books_count
                  from reading_log rl
                  where rl.id_user = $this->id_user
                    and rl.status = 'completed'
                  group by year(rl.end_date), month(rl.end_date)
                  order by year(rl.end_date) asc, month(rl.end_date) asc";

        $result = $this->con->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;

    }
    public function pagesPerMonth(): array
    {
        //
        $query = "select
                    year(rl.end_date) as year,
                    month(rl.end_date) as month,
                    monthname(rl.end_date) AS month_name,
                    sum(b.page_count) as total_pages,
                    count(*) as book_count
                  from reading_log rl
                  
                  left join books b
                    on rl.id_book = b.id
                    
                  where rl.id_user = $this->id_user
                    and rl.status = 'completed'
                  group by year(rl.end_date), month(rl.end_date)
                  order by year(rl.end_date) asc, month(rl.end_date) asc";

        $result = $this->con->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;

    }
    public function topGenres(): array
    {
        $query = "select
                    g.name as genre,
                    count(*) as genre_count
                  from reading_log rl
                  
                  inner join books b
                    on rl.id_book = b.id
                  inner join book_genres bg
                   	on b.id = bg.id_book
                  inner join genres g
                  	on bg.id_genre = g.id
                   
                  where rl.id_user = $this->id_user
                    and rl.status = 'completed'
                  group by g.name
                  order by genre_count desc";

        $result = $this->con->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function topAuthors(): array
    {
        $query = "select
                    b.author,
                    count(*) as author_count
                  from reading_log rl
                  
                  left join books b
                    on rl.id_book = b.id
                   
                  where rl.id_user = $this->id_user
                    and rl.status = 'completed'
                  group by b.author
                  order by author_count desc";

        $result = $this->con->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function booksPerStatus(): array
    {
        $query = "select
                    rl.status,
                    count(*) as books_count
                  from reading_log rl
                  
                  left join books b
                    on rl.id_book = b.id
                   
                  where rl.id_user = $this->id_user
                  group by rl.status
                  order by books_count desc";

        $result = $this->con->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;


    }

    public function calculateXP(): int
    {
        $query = "select
                    sum(b.page_count) as total_pages
                  from reading_log rl
                  
                  left join books b
                    on rl.id_book = b.id
                    
                  where rl.id_user = $this->id_user
                    and rl.status = 'completed'";

        $result = $this->con->query($query);
        $row = $result->fetch_assoc();
        $totalPages = $row['total_pages'] ?? 0;

        // Pretpostavimo da korisnik dobija 1 XP za svaku pročitanu stranicu
        return (int)$totalPages;
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
        // TODO: Implement validationRules() method.
    }
}