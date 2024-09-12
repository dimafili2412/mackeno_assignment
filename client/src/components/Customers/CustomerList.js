import React, { useState } from 'react';
import '../../styles/Customers.css';

function CustomerList({ customers, loading, page, setPage, totalPages, handleDeleteCustomer, handleEditCustomer }) {
    const [editingCustomerId, setEditingCustomerId] = useState(null);
    const [editFormData, setEditFormData] = useState({
        name: '',
        email: '',
        address: '',
        phone_number: '',
    });

    if (loading) {
        return <p>Loading...</p>;
    }

    if (customers.length === 0) {
        return <p>No customers available</p>;
    }

    // Initialize the form with the selected customer data for editing
    const handleEditClick = (customer) => {
        setEditingCustomerId(customer.id);
        setEditFormData({
            name: customer.name,
            email: customer.email,
            address: customer.address,
            phone_number: customer.phone_number,
        });
    };

    // Handle form input changes during editing
    const handleEditFormChange = (e) => {
        const { name, value } = e.target;
        setEditFormData((prevData) => ({
            ...prevData,
            [name]: value,
        }));
    };

    // Save the edited customer data
    const handleSaveClick = () => {
        handleEditCustomer(editingCustomerId, editFormData); // Pass the ID and updated form data
        setEditingCustomerId(null); // Exit edit mode after saving
    };

    // Cancel the edit mode
    const handleCancelClick = () => {
        setEditingCustomerId(null);
    };

    return (
        <div className="customer-list">
            <table className="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {customers.map((customer) => (
                        <tr key={customer.id}>
                            <td>{customer.id}</td>
                            <td>
                                {editingCustomerId === customer.id ? (
                                    <input
                                        type="text"
                                        name="name"
                                        value={editFormData.name}
                                        onChange={handleEditFormChange}
                                        className="input"
                                    />
                                ) : (
                                    customer.name
                                )}
                            </td>
                            <td>
                                {editingCustomerId === customer.id ? (
                                    <input
                                        type="email"
                                        name="email"
                                        value={editFormData.email}
                                        onChange={handleEditFormChange}
                                        className="input"
                                    />
                                ) : (
                                    customer.email
                                )}
                            </td>
                            <td>
                                {editingCustomerId === customer.id ? (
                                    <input
                                        type="text"
                                        name="address"
                                        value={editFormData.address}
                                        onChange={handleEditFormChange}
                                        className="input"
                                    />
                                ) : (
                                    customer.address
                                )}
                            </td>
                            <td>
                                {editingCustomerId === customer.id ? (
                                    <input
                                        type="text"
                                        name="phone_number"
                                        value={editFormData.phone_number}
                                        onChange={handleEditFormChange}
                                        className="input"
                                    />
                                ) : (
                                    customer.phone_number
                                )}
                            </td>
                            <td>
                                {editingCustomerId === customer.id ? (
                                    <>
                                        <button onClick={handleSaveClick} className="button saveButton">
                                            Save
                                        </button>
                                        <button onClick={handleCancelClick} className="button cancelButton">
                                            Cancel
                                        </button>
                                    </>
                                ) : (
                                    <>
                                        <button onClick={() => handleEditClick(customer)} className="button editButton">
                                            Edit
                                        </button>
                                        <button onClick={() => handleDeleteCustomer(customer.id)} className="button deleteButton">
                                            Delete
                                        </button>
                                    </>
                                )}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            {/* Pagination */}
            <div className="pagination">
                <button onClick={() => setPage(page - 1)} disabled={page === 1}>
                    Previous
                </button>
                <span>
                    Page {page} of {totalPages}
                </span>
                <button onClick={() => setPage(page + 1)} disabled={page === totalPages}>
                    Next
                </button>
            </div>
        </div>
    );
}

export default CustomerList;
