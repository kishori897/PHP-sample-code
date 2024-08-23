<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // public function readAll() {
    //     $query = "SELECT * FROM " . $this->table;
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt;
    // }

    public function readPaginated($limit, $offset) {
        $query = "SELECT * FROM " . $this->table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

   

    public function create() {
        // Validate inputs
        if (!$this->validateInputs()) {
            return false;
        }

        // Check if the email already exists
        if ($this->emailExists()) {
            $this->errors[] = 'Email already exists.';
            return false;
        }

        // Hash the password
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Prepare the query
        $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function validateInputs() {
        $valid = true;
        // Clear previous errors
        $this->errors = [];

        // Check if name and email are valid
        if (empty($this->name)) {
            $this->errors[] = 'Name is required.';
            $valid = false;
        }

        if (empty($this->email)) {
            $this->errors[] = 'Email is required.';
            $valid = false;
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email is not valid.';
            $valid = false;
        }

        if (empty($this->password)) {
            $this->errors[] = 'Password is required.';
            $valid = false;
        }

        return $valid;
    }

    private function emailExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function update() {
        $errors = [];
    
        // Validate Name
        if (empty($this->name)) {
            $errors[] = 'Name is required.';
        }
    
        // Validate Email
        if (empty($this->email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is not valid.';
        } else {
            // Check if the email is unique (excluding the current user)
            $query = "SELECT id FROM " . $this->table . " WHERE email = :email AND id != :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $errors[] = 'Email already exists.';
            }
        }
    
        // If there are validation errors, return them
        if (!empty($errors)) {
            $this->errors = $errors;
            return false;
        }
    
        // If validation passes, proceed with the update
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    

    public function verifyPassword($userId, $oldPassword) {
        
        $query = "SELECT password FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        if ($row) {
            // Debugging output
            
            if (password_verify($oldPassword, $row['password'])) {
                
                return true;
            }
        }
        return false;
    }
    
    
    public function updatePassword() {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    
}
?>
