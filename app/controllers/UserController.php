<?php
require_once __DIR__ . '/../models/User.php';


class UserController {
    private $conn;
    private $userModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->userModel = new User($this->conn);
    }

    
    
    public function index() {
        $limit = 2; // Number of records per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $limit; // Calculate offset
    
        // Fetch paginated users
        $stmt = $this->userModel->readPaginated($limit, $offset);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Get total number of users for pagination
        $totalUsers = $this->userModel->countAll();
        $totalPages = ceil($totalUsers / $limit); // Calculate total pages
    
        // Pass data to the view
        $title = 'Users List';
        $content = __DIR__ . '/../views/user/index.php';  // Path to the add view
        include __DIR__ . '/../views/layout.php';  // Path to the layout
        //require __DIR__ . '/../views/user/index.php';
    }
    
    
    public function create() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize input data
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            // Set model properties
            $this->userModel->name = $name;
            $this->userModel->email = $email;
            $this->userModel->password = $password;

            // Attempt to create user
            if ($this->userModel->create()) {
                header('Location: ' . BASE_URL . '/users?success=User created successfully');
                exit;
            } else {
                // Pass errors to the view
                $errors = $this->userModel->errors;
                
            }
        }
        
        // Include the view
        $title = 'Create User';
        $content = __DIR__ . '/../views/user/create.php';  // Path to the add view
        include __DIR__ . '/../views/layout.php';  // Path to the layout
      
    }

    public function edit($id) {
        $this->userModel->id = $id;
        $user = $this->userModel->readOne();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize input data
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            

            // Set model properties
            $this->userModel->name = $name;
            $this->userModel->email = $email;
            
            if ($this->userModel->update()) {
                header('Location: ' . BASE_URL . '/users?success=User data updated successfully');
            }else{
                 // Pass errors to the view
                 $errors = $this->userModel->errors;

            }
        }
        $title = 'Edit User';
        $content = __DIR__ . '/../views/user/edit.php';  // Path to the add view
        include __DIR__ . '/../views/layout.php';  // Path to the layout
        
    }

    public function changePassword($id) {
        $errors = [];
        $this->userModel->id = $id;
        $user = $this->userModel->readOne();
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $oldPassword = trim($_POST['oldpwd']);
            $newPassword = trim($_POST['newpwd']);
            $confirmPassword = trim($_POST['confirmpwd']);
    
            // Validate if all fields are filled
            if (empty($oldPassword)) {
                $errors['oldpwd'] = "Old password is required.";
            }
            if (empty($newPassword)) {
                $errors['newpwd'] = "New password is required.";
            }
            if (empty($confirmPassword)) {
                $errors['confirmpwd'] = "Confirm password is required.";
            }
    
            // Proceed with further validation only if no required field errors
            if (empty($errors)) {
                // Validate the old password
                
                if (!$this->userModel->verifyPassword($this->userModel->id, $oldPassword)) {
                    $errors['oldpwd'] = "Old password does not match222.";
                }
    
                // Validate new password and confirm password match
                if ($newPassword !== $confirmPassword) {
                    $errors['confirmpwd'] = "Confirm password does not match new password.";
                }
    
                // If no errors, update the password
                if (empty($errors)) {
                    $this->userModel->password = password_hash($newPassword, PASSWORD_DEFAULT);
                    if ($this->userModel->updatePassword()) {
                        header('Location: ' . BASE_URL . '/users?success=Password changed successfully');
                        exit;
                    } else {
                        $errors['general'] = "An error occurred. Please try again.";
                    }
                }
            }
        }
        $title = 'Change Password';
        $content = __DIR__ . '/../views/user/change_password.php';  // Path to the add view
        include __DIR__ . '/../views/layout.php';  // Path to the layout
     
    }
    
    

    public function delete($id) {
        $this->userModel->id = $id;
        if ($this->userModel->delete()) {
            header('Location:  ' . BASE_URL . '/users?success=User deleted successfully');
        }
    }
}
?>
