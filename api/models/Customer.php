<?php
/**
 * Customer model for handling logic and CRUD operations
 */
class Customer {
    private $conn;
    private $table = 'customers';
    
    // Customer properties
    public $id;
    public $name;
    public $email;
    public $address;
    public $phone_number;

    /**
     * Constructor to initialize the database connection
     * @param PDO $db PDO database connection object
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new customer record in the database
     * @return bool Returns true if the record is created successfully, false otherwise
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name, email, address, phone_number) VALUES (:name, :email, :address, :phone_number)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve all customer records from the database with pagination
     * @param int $limit Number of records per page
     * @param int $offset Offset for pagination
     * @return array The result containing customer data and pagination info
     */
    public function read($limit, $offset) {
        // Query to count total customers
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table;
        $countStmt = $this->conn->prepare($countQuery);
        $countStmt->execute();
        $totalCustomers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        // Query to get limited customer data for the current page
        $query = "SELECT * FROM " . $this->table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        // Fetch the data
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Calculate total pages
        $totalPages = ceil($totalCustomers / $limit);
        // Return data and pagination info
        return [
            'data' => $customers,
            'totalCustomers' => $totalCustomers,
            'totalPages' => $totalPages
        ];
    }

    /**
     * Find a customer record by its ID
     * @param int $id The customer ID to search for
     * @return array|null Returns an associative array of the customer details or null if not found
     */
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Set properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->phone_number = $row['phone_number'];
            return $row;
        }
        return null; // If no record found
    }

    /**
     * Update an existing customer record in the database
     * @return bool Returns true if the record is updated successfully, false otherwise
     */
    public function update() {
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email, address = :address, phone_number = :phone_number WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Delete a customer record from the database by its ID
     * @return bool Returns true if the record is deleted successfully, false otherwise
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
