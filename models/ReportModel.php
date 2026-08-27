<?php

namespace app\models;

use app\core\Application;
use app\core\BaseModel;
use DateTime;

class ReportModel extends BaseModel
{
    public string $from = '';
    public string $to = '';
    public function getNumberOfBooksPerMonth()
    {
        $id_user = 0;
        $sessions = Application::$app->session->get('user');

        foreach ($sessions as $session) {
            $id_user = $session['id_user'];
        }

        if (!$this->from || $this->from == ''){
            $fromDate = new DateTime('2025-01-01');
            $this->from = $fromDate->format('Y-m-d');
        }
        if (!$this->to || $this->to == ''){
            $toDate = new DateTime();
            $this->to = $toDate->format('Y-m-d');;
        }

        $dbResult = $this->con->query("select MONTHNAME(end_date) as 'month', count(id) as 'number_of_books' 
                                                from reading_log
                                                where id_user = $id_user 
                                                and status = 'completed'
                                                and
                                                 date(end_date) between '$this->from' and '$this->to'
                                                group by MONTHNAME(end_date) 
                                                order by MONTH(end_date) asc;");
        //GROUP BY: po cemu grupisemo; jedna grupa = procitanje knjige u x mesecu
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    public function getNumberOfPagesPerMonth()
    {
        $id_user = 0;
        $sessions = Application::$app->session->get('user');

        foreach ($sessions as $session) {
            $id_user = $session['id_user'];
        }

        if (!$this->from || $this->from == ''){
            $fromDate = new DateTime('2025-01-01');
            $this->from = $fromDate->format('Y-m-d');
        }
        if (!$this->to || $this->to == ''){
            $toDate = new DateTime();
            $this->to = $toDate->format('Y-m-d');;
        }

        $dbResult = $this->con->query("select MONTHNAME(end_date) as 'month', sum(b.page_count) as 'number_of_pages' 
                                                from reading_log rl
                                                inner join books b
                                                on b.id = rl.id_book
                                                where id_user = $id_user 
                                                and status = 'completed'
                                                and
                                                 date(end_date) between '$this->from' and '$this->to'
                                                group by MONTHNAME(end_date) 
                                                order by MONTH(end_date) asc;");
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    public function getNumberOfGenres()
    {
        $id_user = 0;
        $sessions = Application::$app->session->get('user');

        foreach ($sessions as $session) {
            $id_user = $session['id_user'];
        }

        $dbResult = $this->con->query("select g.name as 'genre', count(*) as 'genre_count'
                                              from reading_log rl
                                              
                                              inner join books b
                                                on rl.id_book = b.id
                                              inner join book_genres bg
                                                on b.id = bg.id_book
                                              inner join genres g
                                                on bg.id_genre = g.id
                                               
                                              where rl.id_user = $id_user
                                                and rl.status = 'completed'
                                              group by g.name
                                              order by genre_count desc
                                              limit 10;");


        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    public function getNumberOfBooksPerStatus()
    {
        $id_user = 0;
        $sessions = Application::$app->session->get('user');

        foreach ($sessions as $session) {
            $id_user = $session['id_user'];
        }

        $dbResult = $this->con->query("select rl.status as 'status', count(b.id) as 'books_count' 
                                                from reading_log rl
                                                inner join books b
                                                on b.id = rl.id_book
                                                where id_user = $id_user
                                                group by rl.status
                                                order by 'books_count' desc;");
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    public function getNumberOfBooksPerPageCount()
    {
        $id_user = 0;
        $sessions = Application::$app->session->get('user');

        foreach ($sessions as $session) {
            $id_user = $session['id_user'];
        }

        $dbResult = $this->con->query("select 
                                                case
                                                    when b.page_count < 300 then '< 300 pages'
                                                    when b.page_count between 300 and 499 then '300-499 pages'
                                                    else '500+ pages'
                                                end as page_range,
                                                count(*) as books_count

                                                from reading_log rl
                                                inner join books b on rl.id_book = b.id
                                                
                                                where rl.id_user = $id_user
                                                  and rl.status = 'completed'
                                                group by page_range
                                                order by case
                                                    when b.page_count < 300 then 1
                                                    when b.page_count between 300 and 499 then 2
                                                    else 3
                                                end;");
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    // ADMIN
    public function getBooksPerUser()
    {
        if (!$this->from || $this->from == ''){
            $fromDate = new DateTime('2024-01-01');
            $this->from = $fromDate->format('Y-m-d');
        }
        if (!$this->to || $this->to == ''){
            $toDate = new DateTime();
            $this->to = $toDate->format('Y-m-d');;
        }

        $dbResult = $this->con->query("select u.first_name as name, count(b.id) as `number_of_books`
                                                from reading_log rl
                                                left join users u
                                                on u.id = rl.id_user
                                                inner join books b
                                                on b.id = rl.id_book
                                                where rl.status = 'completed'
                                                and
                                                 date(end_date) between '$this->from' and '$this->to'
                                                group by u.first_name  
                                                order by `number_of_books` desc;");
        //GROUP BY: po cemu grupisemo; jedna grupa = user sa brojem knjiga
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
    }

    public function getBooksPerGenre()
    {
        if (!$this->from || $this->from == ''){
            $fromDate = new DateTime('2025-01-01');
            $this->from = $fromDate->format('Y-m-d');
        }
        if (!$this->to || $this->to == ''){
            $toDate = new DateTime();
            $this->to = $toDate->format('Y-m-d');;
        }

        $dbResult = $this->con->query("select g.name as name, count(b.id) as `books_completed`
                                                from reading_log rl
                                                    
                                                inner join books b
                                                on rl.id_book = b.id
                                                inner join book_genres bg
                                                on b.id = bg.id_book
                                                inner join genres g
                                                on bg.id_genre = g.id
                                                
                                                where rl.status = 'completed'
                                                and
                                                 date(end_date) between '$this->from' and '$this->to'
                                                group by g.name
                                                order by `books_completed` desc;");
        //GROUP BY: po cemu grupisemo; jedna grupa = genre sa brojem knjiga
        $resultArray = [];

        while ($result = $dbResult->fetch_assoc()) {
            $resultArray[] = $result;
        }

        echo json_encode($resultArray);
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

    public function validationRules(): array
    {
        return [];
    }
}