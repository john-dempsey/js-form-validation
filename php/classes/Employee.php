<?php

class Employee {

    public $id;
    public $name;
    public $ppsn;
    public $salary;
    public $department_id;

    public function __construct($props = null) {
        if ($props != null) {
            if (array_key_exists("id", $props)) {
                $this->id = $props["id"];
            }
            $this->name = $props["name"];
            $this->ppsn  = $props["ppsn"];
            $this->salary = $props["salary"];
            $this->department_id  = $props["department_id"];
        }
    }

    public function save() {
        try {
            $db = new DB();
            $conn = $db->open();
        
            $params = [
                ":name" => $this->name,
                ":ppsn"  => $this->ppsn,
                ":salary" => $this->salary,
                ":department_id"  => $this->department_id
            ];

            if ($this->id === null) {
                $sql = 
                    "INSERT INTO employees " . 
                    "(name, ppsn, salary, department_id) VALUES " . 
                    "(:name, :ppsn, :salary, :department_id)";
            }
            else {
                $sql = "UPDATE employees SET " .
                       "name = :name, " .
                       "ppsn = :ppsn, " .
                       "salary = :salary, " .
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
                throw new Exception("Failed to save employee.");
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
        
                $sql = "DELETE FROM employees WHERE id = :id" ;
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
                    throw new Exception("Failed to delete employee.");
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

    public function projects() {
        return Project::findByEmployeeId($this->id);
    }

    public static function findAll() {
        $employees = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM employees";
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
                    $employee = new Employee($row);
                    $employees[] = $employee;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $employees;
    }

    public static function findById($id) {
        $employee = null;

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM employees WHERE id = :id";
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
                $employee = new Employee($row);
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $employee;
    }

    public static function findByProjectId($id) {
        $employees = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM employees WHERE id IN " .
                   "(SELECT employee_id FROM employee_project WHERE project_id = :project_id)";
            $params = [
                ":project_id" => $id
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
                    $employee = new Employee($row);
                    $employees[] = $employee;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $employees;
    }

    public static function findByDepartmentId($id) {
        $employees = array();

        try {
            $db = new DB();
            $conn = $db->open();

            $sql = "SELECT * FROM employees WHERE department_id = :department_id";
            $params = [
                ":department_id" => $id
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
                    $employee = new Employee($row);
                    $employees[] = $employee;

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        finally {
            if ($db !== null && $db->isOpen()) {
                $db->close();
            }
        }

        return $employees;
    }
}