<?php

class CreditCard {

    public $id;
    public $type;
    public $name;
    public $number;
    public $exp_month;
    public $exp_year;
    public $cvv;

    public function __construct($props = null) {
        if ($props !== null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->name = $props["name"];
            $this->number = $props["number"];
            $this->type = $props["type"];
            $this->exp_month = $props["exp_month"];
            $this->exp_year = $props["exp_year"];
            $this->cvv = $props["cvv"];
        }
    }

    public function save() {
        $db = null;
        try {
            $db = new DB();
            $conn = $db->open();

            $params = [
                "type" => $this->type,
                "name" => $this->name,
                "number" => $this->number,
                "exp_month" => $this->exp_month,
                "exp_year" => $this->exp_year,
                "cvv" => $this->cvv
            ];
            if ($this->id === null) {
                $sql = "INSERT INTO credit_cards(type, name, number, exp_month, exp_year, cvv) " .
                       "VALUES (:type, :name, :number, :exp_month, :exp_year, :cvv)";
            }
            else {
                $sql = "UPDATE credit_cards SET " . 
                       " type = :type, " .
                       " name = :name, " .
                       " number = :number, " .
                       " exp_month = :exp_month, " .
                       " exp_year = :exp_year, " .
                       " cvv = :cvv " .
                       " WHERE id = :id";
                
                $params["id"] = $this->id;
            }
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute($params);
            if ($status === FALSE) {
                $error_info = $stmt->errorInfo();
                throw new Exception("Error executing SQL: " . $error_info[2]);
            }
        
            if ($stmt->rowCount() === 0) {
                throw new Exception("SQL insert/update failed!");
            }

            if ($this->id === null) {
                $this->id = $conn->lastInsertId();
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }
    }

    public function delete() {
        $db = null;
        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "DELETE FROM credit_cards WHERE id = :id";
            $params = [
                "id" => $this->id
            ];
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute($params);

            if ($status === FALSE) {
                throw new Exception("Error: " . $stmt->errorInfo()[2]);
            }

            $this->id = null;
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }
    }

    public static function findAll() {
        $cards = array();

        $db = null;
        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM credit_cards";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute();
            if ($status === FALSE) {
                $error_info = $stmt->errorInfo();
                throw new Exception("Error executing SQL: " . $error_info[2]);
            }
        
            if ($stmt->rowCount() !== 0) {
                $card = $stmt->fetch(PDO::FETCH_ASSOC);
                while ($card !== FALSE) {
                    $cards[] = new CreditCard($card);
                    $card = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $cards;
    }

    public static function findById($id) {
        $card = null;

        $db = null;
        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM credit_cards WHERE id = :id";
            $params = [
                "id" => $id
            ];
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute($params);
            if ($status === FALSE) {
                $error_info = $stmt->errorInfo();
                throw new Exception("Error executing SQL: " . $error_info[2]);
            }
        
            if ($stmt->rowCount() !== 0) {
                $card = $stmt->fetch(PDO::FETCH_ASSOC);
                $card = new CreditCard($card);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $card;
    }
}