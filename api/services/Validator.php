<?php

/**
 * Provides methods to validate input fields 
 */
class ValidatorService
{
    /**
     * Validate non-empty string for a field
     * @param string $string
     * @param string $fieldName
     * @return string|null
     */
    public function empty($string, $fieldName = 'Field')
    {
        if (empty(trim($string))) {
            return ucfirst($fieldName) . " cannot be empty.";
        }
        return null;
    }
    
    /**
     * Validate Israeli phone number
     * Valid formats: +972-5X-XXX-XXXX or 05X-XXX-XXXX
     * @param string $phoneNumber
     * @param boolean $require Check whether the string is empty
     * @return string|null
     */
    public function phone($phoneNumber, $require = false)
    {
        if($require && $empty = $this->empty($phoneNumber, "Phone number")) return $empty; 
        // Regex to match Israeli phone number format
        $pattern = '/^(?:\+972|0)(5[0-9])[ -]?[0-9]{3}[ -]?[0-9]{4}$/';
        if (!preg_match($pattern, $phoneNumber)) {
            return "Invalid Israeli phone number.";
        }
        return null;
    }

    /**
     * Validate email address
     * @param string $email
     * @param boolean $require Check whether the string is empty
     * @return string|null
     */
    public function email($email, $require = false)
    {
        if($require && $empty = $this->empty($email, "Email")) return $empty; 
        // Check if email is in a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return null; // Valid email
    }

    /**
     * Validate password
     * Must contain at least 8 characters, one uppercase letter, one number, and one special character
     * @param string $password
     * @return string|null
     */
    public function password($password)
    {
        if($empty = $this->empty($password, "Password")) return $empty; 
        // Regex to check password format
        $pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($pattern, $password)) {
            return "Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.";
        }
        return null;
    }
}

