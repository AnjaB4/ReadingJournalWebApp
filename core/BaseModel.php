<?php

namespace app\core;

use mysqli;

abstract class BaseModel
{
    public const RULE_EMAIL = "rule_email";
    public const RULE_REQUIRED = "rule_required";
    public const RULE_POSITIVE = "rule_positive";
    public const RULE_UNIQUE = "rule_unique";
    public const RULE_DATE = "rule_date";
    public const RULE_NO_QUOTES = "rule_no_quotes";


    public $errors;
    private DbConnection $db;
    public mysqli $con;
    public function __construct()
    {
        $this->db = new DbConnection();
        $this->con = $this->db->connect();
    }

    abstract public function tableName(); //Abstract Factory design pattern

    abstract public function readColumns(); //Abstract Factory design pattern
    abstract public function editColumns();
    abstract public function validationRules();

    public function one($where)
    {
        $tableName = $this->tableName();
        $columns = $this->readColumns();

        $query = "select " .  implode(',', $columns) . " from $tableName $where limit 1";

        $dbResult = $this->con->query($query);
        $result = $dbResult->fetch_assoc();

        if ($result != null) {
            $this->mapData($result);
        }
    }

    public function all($where): array
    {
        $tableName = $this->tableName();
        $columns = $this->readColumns();

        $query = "select " .  implode(',', $columns) . " from $tableName $where";

        $dbResult = $this->con->query($query);

        $resultArray = [];                                  //
                                                            //
        while ($result = $dbResult->fetch_assoc()) {        //
            $resultArray[] = $result;                       //
        }                                                   //
        return $resultArray;                                //
    }

    public function update($where)
    {
        $tableName = $this->tableName();
        $columns = $this->editColumns();
        $columnsHelper = array_map(fn($attr) => ":$attr", $columns);

        $commonHelper = [];

        for ($i = 0; $i < count($columnsHelper); $i++) {
            $commonHelper[] = "$columns[$i] = $columnsHelper[$i]";
        }

        $query = "update $tableName set " . implode(',', $commonHelper) . " $where";

        foreach ($columns as $attribute) {
            $query = str_replace(":$attribute", is_string($this->{$attribute}) ? '"' . $this->{$attribute} . '"' : $this->{$attribute}, $query);
        }
//var_dump($query);exit;
        $this->con->query($query);
    }

    public function insert()
    {
        $tableName = $this->tableName();
        $columns = $this->editColumns();
        $columnsHelper = array_map(fn($attr) => ":$attr", $columns);

        $query = "insert into $tableName (" . implode("," , $columns) . ") values (" . implode("," , $columnsHelper) . ")";

        foreach ($columns as $attribute) {
            $query = str_replace(":$attribute", is_string($this->{$attribute}) ? '"' . $this->{$attribute} . '"' : $this->{$attribute}, $query);
        }

        $this->con->query($query);
    }

    public function mapData($data)
    {
        if ($data != null) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function validate()
    {
        $allRules = $this->validationRules();

        foreach ($allRules as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {
                if ($rule == self::RULE_REQUIRED) {
                    if (!$value){
                        $this->errors[$attribute][] = "This field is required";
                    }
                }

                if ($rule == self::RULE_EMAIL) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$attribute][] = "Email must be in Email format";
                    }
                }

                if ($rule == self::RULE_POSITIVE) {
                    if ($value !== null && $value <= 0) {
                        $this->errors[$attribute][] = "Value must be greater than 0";
                    }
                }

                if ($rule == self::RULE_UNIQUE) { //DODAJ i u odg Model u listu gresaka da bi radilo
                    if ($this->checkUniqueEmail($value)) {
                        $this->errors[$attribute][] = "Email already exists";
                    }
                }

                if ($rule == self::RULE_DATE) {
                    $startDate = $this->start_date;
                    $endDate = $value; // $value je $this->{$attribute}, tj. end_date

                    if ($startDate && $endDate && $endDate < $startDate) {
                        $this->errors[$attribute][] = "End date cannot be before start date";
                    }
                }

                if ($rule == self::RULE_NO_QUOTES){
                    if (str_contains($value, '"') || str_contains($value, "'")) {
                        $this->errors[$attribute][] = "Quotes are not allowed";
                    }

                }

            }

        }

    }

    public function checkUniqueEmail($email): bool
    {
        $query = "select email from users where email = '$email'";

        $dbResult = $this->con->query($query);
        $result = $dbResult->fetch_assoc();

        if ($result != null) {
            return true;
        }
        return false;
    }

    public function delete($where): bool
    {
        $tableName = $this->tableName();

        $query = "delete from $tableName $where";

        return $this->con->query($query);

    }
////////SEARCH TREBA SREDITI, za books npr trazi po svemu pa cak i nazivu slike //////////////
    public function search(string $search): array
    {
        $tableName = $this->tableName();
        $columns = $this->readColumns();


        $search = $this->con->real_escape_string($search);

        $conditions = [];

        // razdvoj kolone
        foreach ($columns as $col) {
            $conditions[] = "$col like '%$search%'";
        }
        
        $query = "select " . implode(',', $columns) . "
                from $tableName
            where " . implode(" or ", $conditions);
     

        $dbResult = $this->con->query($query);

        $resultArray = [];                                     
        while ($result = $dbResult->fetch_assoc()) {       
            $resultArray[] = $result;                     
        } 
                                                         
        return $resultArray; 

    }

}