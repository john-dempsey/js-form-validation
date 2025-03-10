<?php

class Course {

    public $id;
    public $title;
    public $description;
    public $code;
    public $department_id;

    public function __construct($props = null) {
        if ($props != null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->title = $props["title"];
            $this->description  = $props["description"];
            $this->code = $props["code"];
            $this->department_id  = $props["department_id"];
        }
    }

    public function save() {
        try {
            $db = new DB();
            $conn = $db->open();
        
            $params = [
                ":title" => $this->title,
                ":description"  => $this->description,
                ":code" => $this->code,
                ":department_id"  => $this->department_id
            ];

            if ($this->id === null) {
                $sql = 
                    "INSERT INTO courses " . 
                    "(title, description, code, department_id) VALUES " . 
                    "(:title, :description, :code, :department_id)";
            }
            else {
                $sql = "UPDATE courses SET " .
                       "title = :title, " .
                       "description = :description, " .
                       "code = :code, " .
                       "department_id = :department_id " .
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
                throw new Exception("Failed to save course.");
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
        
                $sql = "DELETE FROM courses WHERE id = :id" ;
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
                    throw new Exception("Failed to delete course.");
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

    public function department() {
        return Department::findById($this->department_id);
    }

    public function modules() {
        return Module::findByCourseId($this->id);
    }

    public static function findAll() {
        $courses = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM courses";
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
                    $course = new Course($row);
                    $courses[] = $course;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $courses;
    }

    public static function findById($id) {
        $course = null;

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM courses WHERE id = :id";
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
                $course = new Course($row);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $course;
    }

    public static function findByDepartmentId($id) {
        $courses = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM courses WHERE department_id = :department_id";
            $params = [
                ":department_id" => $id
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
                    $course = new Course($row);
                    $courses[] = $course;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $courses;
    }

    public static function findByModuleId($id) {
        $courses = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM courses WHERE id IN " .
                   "(SELECT course_id FROM course_module WHERE module_id = :module_id)";
            $params = [
                ":module_id" => $id
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
                    $course = new Course($row);
                    $courses[] = $course;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $courses;
    }

}