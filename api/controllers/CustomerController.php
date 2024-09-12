<?php
require_once __DIR__ . '/../models/Customer.php';

/**
 * CustomerController handles all customer actions. It interacts with the Customer model for database operations
 */
class CustomerController {
    private $db;
    private $customer;
    private $validator;

    /**
     * Constructor that initializes the controller with the database connection and Customer model
     * @param PDO $db A PDO connection object
     * @param ValidatorService $validator An instance of the ValidatorService for validating inputs
     */
    public function __construct($db, $validator) {
        $this->db = $db;
        $this->customer = new Customer($db);
        $this->validator = $validator;
    }

    /**
     * Create a new customer using the provided data
     * @param array $data An associative array containing customer details 
     * @return string A JSON response with a success or failure message
     */
    public function createCustomer($data) {
        // Validate inputs
        $errors = [];
        if ($error = $this->validator->empty($data['name'], 'Name')) $errors[] = $error;
        if ($error = $this->validator->email($data['email'], true)) $errors[] = $error;
        if ($error = $this->validator->phone($data['phone_number'], true)) $errors[] = $error;
        if (!empty($errors)) {
            http_response_code(400); 
            return json_encode(['message' => 'Customer creation failed', 'errors' => $errors]);
        }
        // Create the customer
        $this->customer->name = $data['name'];
        $this->customer->email = $data['email'];
        $this->customer->address = $data['address'];
        $this->customer->phone_number = $data['phone_number'];
        if ($this->customer->create()) {
            http_response_code(201); 
            return json_encode(['message' => 'Customer created successfully']);
        } else {
            http_response_code(500); 
            return json_encode(['message' => 'Customer creation failed']);
        }
    }


    /**
     * Get all customers with pagination
     * @param int $page The current page number
     * @param int $perPage The number of customers per page
     * @return string The JSON response containing customer data and pagination
     */
    public function getCustomers($page, $perPage) {
        // Calculate the offset for pagination
        $offset = ($page - 1) * $perPage;
        // Get customers with pagination
        $result = $this->customer->read($perPage, $offset);
        // Prepare the response
        $response = [
            'data' => $result['data'],
            'page' => $page,
            'totalPages' => $result['totalPages'],
            'totalCustomers' => $result['totalCustomers']
        ];
        // Return the response as JSON
        return json_encode($response);
    }

    /**
     * Update an existing customer using the provided data
     * @param array $data Array containing updated customer details
     * @return string A JSON response with a success or failure message
     */
    public function updateCustomer($data) {
        // Check if customer exists
        if (!$this->customer->findById($data['id'])) {
            http_response_code(404);
            return json_encode(['message' => 'Customer not found']);
        }
        // Validations
        $errors = [];
        if ($error = $this->validator->empty($data['name'], 'Name')) $errors[] = $error;
        if ($error = $this->validator->email($data['email'], true)) $errors[] = $error;
        if ($error = $this->validator->phone($data['phone_number'], true)) $errors[] = $error;
        if (!empty($errors)) {
            http_response_code(400);
            return json_encode(['message' => 'Customer update failed', 'errors' => $errors]);
        }
        // Customer pathing
        $this->customer->id = $data['id'];
        $this->customer->name = $data['name'];
        $this->customer->email = $data['email'];
        $this->customer->address = $data['address'];
        $this->customer->phone_number = $data['phone_number'];
        if ($this->customer->update()) {
            return json_encode(['message' => 'Customer updated successfully']);
        } else {
            http_response_code(500);
            return json_encode(['message' => 'Customer update failed']);
        }
    }

    /**
     * Delete a customer by their ID
     * @param int $id The ID of the customer to delete
     * @return string A JSON response with a success or failure message
     */
    public function deleteCustomer($id) {
        $this->customer->id = $id;
        // Check if customer exists before deletion
        if (!$this->customer->findById($id)) {
            http_response_code(404);
            return json_encode(['message' => 'Customer not found']);
        }
        if ($this->customer->delete()) {
            http_response_code(200);
            return json_encode(['message' => 'Customer deleted successfully']);
        } else {
            http_response_code(500);
            return json_encode(['message' => 'Customer deletion failed']);
        }
    }
}
