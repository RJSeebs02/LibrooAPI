<?php
class CartItem{
 
    // database connection and table name
    private $conn;
    private $table_name = "carting_item";
 
    // object properties
    public $carting_item_id;
    public $cart_id;
    public $book_id;
    public $product_quantity;
 
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
        
        // Query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    cart_id = :cart_id, book_id = :book_id, product_quantity = :product_quantity";
        
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->cart_id = htmlspecialchars(strip_tags($this->cart_id));
        $this->book_id = htmlspecialchars(strip_tags($this->book_id));
        $this->product_quantity = htmlspecialchars(strip_tags($this->product_quantity));
        
        // Bind values
        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":product_quantity", $this->product_quantity);
        
        // Execute query
        if ($stmt->execute()) {
            return true; // Record inserted successfully
        }
        
        return false; // Error in execution
    }

    //read books in cart
    function read(){
 
        // select all query
        $query = "SELECT b.*, ci.product_quantity
        FROM books AS b
        INNER JOIN carting_item AS ci ON b.book_id = ci.book_id
        INNER JOIN carting AS c ON ci.cart_id = c.cart_id
        INNER JOIN users AS u ON c.user_id = u.user_id
        WHERE u.user_id = :user_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create user
    function update() {
        
        // Query to insert record
        $query = "UPDATE " . $this->table_name . " 
              SET product_quantity = :product_quantity 
              WHERE cart_id = :cart_id AND book_id = :book_id";
        
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->cart_id = htmlspecialchars(strip_tags($this->cart_id));
        $this->book_id = htmlspecialchars(strip_tags($this->book_id));
        $this->product_quantity = htmlspecialchars(strip_tags($this->product_quantity));
        
        // Bind values
        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":product_quantity", $this->product_quantity);
        
        // Execute query
        if ($stmt->execute()) {
            return true; // Record inserted successfully
        }
        
        return false; // Error in execution
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE cart_id = :cart_id AND book_id = :book_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->bindParam(':book_id', $this->book_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>