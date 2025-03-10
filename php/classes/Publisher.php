<?php

class Publisher {

    public $id;
    public $title;
    public $address;
    public $phone;
    public $website;

    public function __construct($props = null) {
        if ($props != null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->title = $props["title"];
            $this->address  = $props["address"];
            $this->phone = $props["phone"];
            $this->website  = $props["website"];
        }
    }

    public function save() {
        try {
            $db = new DB();
            $conn = $db->open();
        
            $params = [
                ":title" => $this->title,
                ":address"  => $this->address,
                ":phone" => $this->phone,
                ":website"  => $this->website
            ];

            if ($this->id === null) {
                $sql = 
                    "INSERT INTO publishers " . 
                    "(title, address, phone, website) VALUES " . 
                    "(:title, :address, :phone, :website)";
            }
            else {
                $sql = "UPDATE publishers SET " .
                       "title = :title, " .
                       "address = :address, " .
                       "phone = :phone, " .
                       "website = :website " .
                       "WHERE id = :id" ;

                $params[":id"] = $this->id;
            }
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute($params);
        
            if (!$status) {
                $error_info = $stmt->errorInfo();
                $message = sprintf(
                    "SQLSTATE error code: %d; error message: %s",
                    $error_info[0],
                    $error_info[2]
                );
                throw new Exception($message);  
            }
        
            if ($stmt->rowCount() !== 1) {
                throw new Exception("Failed to save publisher.");
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
            if ($this->id !== null) {
                $db = new DB();
                $conn = $db->open();
        
                $sql = "DELETE FROM publishers WHERE id = :id" ;
                $params = [
                    ":id" => $this->id
                ];
                $stmt = $conn->prepare($sql);
                $status = $stmt->execute($params);
        
                if (!$status) {
                    $error_info = $stmt->errorInfo();
                    $message = sprintf(
                        "SQLSTATE error code: %d; error message: %s",
                        $error_info[0],
                        $error_info[2]
                    );
                    throw new Exception($message);  
                }
        
                if ($stmt->rowCount() !== 1) {
                    throw new Exception("Failed to delete publisher.");
                }
                $this->id = null;
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }
    }

    public function books() {
        return Book::findByPublisherId($this->id);
    }

    public static function findAll() {
        $publishers = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM publishers";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute();

            if (!$status) {
                $error_info = $stmt->errorInfo();
                $message = sprintf(
                    "SQLSTATE error code: %d; error message: %s",
                    $error_info[0],
                    $error_info[2]
                );
                throw new Exception($message);  
            }

            if ($stmt->rowCount() !== 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                while ($row !== FALSE) {
                    $publisher = new Publisher($row);
                    $publishers[] = $publisher;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $publishers;
    }

    public static function findById($id) {
        $publisher = null;

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM publishers WHERE id = :id";
            $params = [
                ":id" => $id
            ];
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute($params);

            if (!$status) {
                $error_info = $stmt->errorInfo();
                $message = sprintf(
                    "SQLSTATE error code: %d; error message: %s",
                    $error_info[0],
                    $error_info[2]
                );
                throw new Exception($message);
            }

            if ($stmt->rowCount() !== 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $publisher = new Publisher($row);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $publisher;
    }
}