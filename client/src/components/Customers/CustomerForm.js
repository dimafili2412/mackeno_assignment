import React, { useState } from 'react';
import { addCustomer } from '../../services/api';
import '../../styles/Customers.css';

function CustomerForm({ fetchCustomers }) {
    const [newCustomer, setNewCustomer] = useState({
        name: '',
        email: '',
        address: '',
        phone_number: '',
    });
    const [errors, setErrors] = useState([]);
    const [successMessage, setSuccessMessage] = useState('');

    // Handle input change
    const handleInputChange = (e) => {
        setNewCustomer({
            ...newCustomer,
            [e.target.name]: e.target.value,
        });
    };

    // Handle form submission for adding a new customer
    const handleAddCustomer = async (e) => {
        e.preventDefault();
        // Clear previous errors and success messages before submitting
        setErrors([]);
        setSuccessMessage('');
        try {
            const response = await addCustomer(newCustomer);
            if (response.status === 201) {
                setSuccessMessage('Customer added successfully!');
                setNewCustomer({
                    name: '',
                    email: '',
                    address: '',
                    phone_number: '',
                });
                setErrors([]);
                fetchCustomers();
            } else {
                setErrors(response.data.errors);
            }
        } catch (err) {
            console.log(err);
            setErrors(['Failed to add customer.']);
        }
    };

    return (
        <div>
            <form onSubmit={handleAddCustomer}>
                <div className="formGroup">
                    <label>Name:</label>
                    <input
                        type="text"
                        name="name"
                        value={newCustomer.name}
                        onChange={handleInputChange}
                        required
                        className="input"
                    />
                </div>
                <div className="formGroup">
                    <label>Email:</label>
                    <input
                        type="email"
                        name="email"
                        value={newCustomer.email}
                        onChange={handleInputChange}
                        required
                        className="input"
                    />
                </div>
                <div className="formGroup">
                    <label>Address:</label>
                    <input
                        type="text"
                        name="address"
                        value={newCustomer.address}
                        onChange={handleInputChange}
                        required
                        className="input"
                    />
                </div>
                <div className="formGroup">
                    <label>Phone Number:</label>
                    <input
                        type="text"
                        name="phone_number"
                        value={newCustomer.phone_number}
                        onChange={handleInputChange}
                        required
                        className="input"
                    />
                </div>
                <button type="submit" className="submitButton">
                    Add Customer
                </button>
            </form>

            {/* Display Errors */}
            {errors.length > 0 && (
                <div className="errorBox">
                    {errors.map((error, index) => (
                        <p key={index}>{error}</p>
                    ))}
                </div>
            )}

            {/* Display Success Message */}
            {successMessage && <p className="successMessage">{successMessage}</p>}
        </div>
    );
}

export default CustomerForm;
