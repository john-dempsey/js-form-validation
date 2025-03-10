<?php

class Module {

    public $id;
    public $title;
    public $description;
    public $credits;
    public $semester;

    public function __construct($props = null) {
        if ($props != null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->title = $props["title"];
            $this->description  = $props["description"];
            $this->credits = $props["credits"];
            $this->semester  = $props["semester"];
        }
    }

    public function save() {
        try {
            $db = new DB();
            $conn = $db->open();
        
            $params = [
                ":title" => $this->title,
                ":description"  => $this->description,
                ":credits" => $this->credits,
                ":semester"  => $this->semester
            ];

            if ($this->id === null) {
                $sql = 
                    "INSERT INTO modules " . 
                    "(title, description, credits, semester) VALUES " . 
                    "(:title, :description, :credits, :semester)";
            }
            else {
                $sql = "UPDATE modules SET " .
                       "title = :title, " .
                       "description = :description, " .
                       "credits = :credits, " .
                       "semester = :semester " .
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
                throw new Exception("Failed to save module.");
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
        
                $sql = "DELETE FROM modules WHERE id = :id" ;
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
                    throw new Exception("Failed to delete module.");
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

    public function courses() {
        return Course::findByModuleId($this->id);
    }

    public static function findAll() {
        $modules = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM modules";
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
                    $module = new Module($row);
                    $modules[] = $module;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $modules;
    }

    public static function findById($id) {
        $module = null;

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM modules WHERE id = :id";
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
                $module = new Module($row);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $module;
    }

    public static function findByCourseId($id) {
        $modules = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM modules WHERE id IN " .
                   "(SELECT module_id FROM course_module WHERE course_id = :course_id)";
            $params = [
                ":course_id" => $id
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
                    $module = new Module($row);
                    $modules[] = $module;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $modules;
    }
}