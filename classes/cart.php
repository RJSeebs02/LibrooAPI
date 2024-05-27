<?php
class Cart{
 
    // database connection and table name
    private $conn;
    private $table_name = "carting";
 
    // object properties
    public $cart_id;
    public $user_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // Method to get user_id from user_username
    function getUserIdByUsername($user_username) {
        // Query to get the user_id based on user_username
        $query = "SELECT user_id FROM users WHERE user_username = ?";
        
        // Prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the user_username to the query
        $stmt->bindParam(1, $user_username);
        
        // Execute the query
        $stmt->execute();
        
        // Check if a row was returned
        if ($stmt->rowCount() > 0) {
            // Fetch the user_id from the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['user_id'];
        } else {
            return null; // No user found with the given username
        }
    }
    
    // Method to get cart_id from user_id
    function getCartIdByUserId($user_id) {
        // Query to get the user_id based on user_username
        $query = "SELECT cart_id FROM carting WHERE user_id = ?";
        
        // Prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the user_username to the query
        $stmt->bindParam(1, $user_id);
        
        // Execute the query
        $stmt->execute();
        
        // Check if a row was returned
        if ($stmt->rowCount() > 0) {
            // Fetch the user_id from the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cart_id'];
        } else {
            return null; // No user found with the given username
        }
    }

    // create user
    function create() {
        // Query to check if the cart already exists
        $check_query = "SELECT cart_id FROM " . $this->table_name . " WHERE user_id = ?";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(1, $this->user_id);
        $check_stmt->execute();
        
        // If the username already exists, return false
        if ($check_stmt->rowCount() > 0) {
            return false; // Username already exists
        }
        
        // Query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    user_id = :user_id";
        
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        
        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        
        // Execute query
        if ($stmt->execute()) {
            return true; // Record inserted successfully
        }
        
        return false; // Error in execution
    }

    // read products
    function read(){
 
        // select all query
        $query = "SELECT * FROM books INNER JOIN carting_item ON books.book_id=carting_item_id INNER JOIN carting ON carting_item.cart_id=carting.cart_id INNER JOIN users ON carting.user_id=users.user_id ORDER BY books.book_id DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>