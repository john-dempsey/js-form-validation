<?php

class Book {

    public $id;
    public $title;
    public $isbn;
    public $price;
    public $publisher_id;

    public function __construct($props = null) {
        if ($props != null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->title = $props["title"];
            $this->isbn  = $props["isbn"];
            $this->price = $props["price"];
            $this->publisher_id  = $props["publisher_id"];
        }
    }

    public function save() {
        try {
            $db = new DB();
            $conn = $db->open();
        
            $params = [
                ":title" => $this->title,
                ":isbn"  => $this->isbn,
                ":price" => $this->price,
                ":publisher_id"  => $this->publisher_id
            ];

            if ($this->id === null) {
                $sql = 
                    "INSERT INTO books " . 
                    "(title, isbn, price, publisher_id) VALUES " . 
                    "(:title, :isbn, :price, :publisher_id)";
            }
            else {
                $sql = "UPDATE books SET " .
                       "title = :title, " .
                       "isbn = :isbn, " .
                       "price = :price, " .
                       "publisher_id = :publisher_id " .
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
                throw new Exception("Failed to save book.");
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
        
                $sql = "DELETE FROM books WHERE id = :id" ;
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
                    throw new Exception("Failed to delete book.");
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

    public function publisher() {
        return Publisher::findById($this->publisher_id);
    }

    public function authors() {
        return Author::findByBookId($this->id);
    }

    public static function findAll() {
        $books = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM books";
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
                    $book = new Book($row);
                    $books[] = $book;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $books;
    }

    public static function findById($id) {
        $book = null;

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM books WHERE id = :id";
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
                $book = new Book($row);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $book;
    }

    public static function findByPublisherId($id) {
        $books = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM books WHERE publisher_id = :publisher_id";
            $params = [
                ":publisher_id" => $id
            ];  
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
                    $book = new Book($row);
                    $books[] = $book;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $books;
    }

    public static function findByBookId($id) {
        $authors = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM books WHERE id IN " .
                   "(SELECT book_id FROM author_book WHERE author_id = :author_id)";
            
            $params = [
                ":author_id" => $id
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
                while ($row !== FALSE) {
                    $author = new Author($row);
                    $authors[] = $author;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $authors;
    }
}