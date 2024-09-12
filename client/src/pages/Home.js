import React, { useState, useEffect } from 'react';
import CustomerList from '../components/Customers/CustomerList';
import CustomerForm from '../components/Customers/CustomerForm';
import { getCustomers, deleteCustomer, updateCustomer } from '../services/api';

function Home() {
    const [customers, setCustomers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const perPage = 5;

    // Fetch customers and populate customer list
    const fetchCustomers = async () => {
        setLoading(true);
        try {
            const response = await getCustomers(page, perPage);
            setCustomers(response.data.data || []);
            setTotalPages(response.data.totalPages || 1);
        } catch (err) {
            console.error('Failed to fetch customers', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchCustomers();
    }, [page]);

    const handleDeleteCustomer = async (customerId) => {
        try {
            await deleteCustomer(customerId);
            fetchCustomers();
        } catch (error) {
            console.error('Failed to delete customer', error);
        }
    };

    // Handle submit customer edit form
    const handleEditCustomer = async (customerId, updatedData) => {
        try {
            await updateCustomer({ id: customerId, ...updatedData });
            fetchCustomers();
        } catch (error) {
            console.error('Failed to update customer', error);
        }
    };

    // Handle logout function
    const handleLogout = () => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    };

    return (
        <div className="home-container">
            <div className="header">
                <h2>Customer Management</h2>
                <button onClick={handleLogout} className="logoutButton">
                    Logout
                </button>
            </div>
            {/* Customer List and Form */}
            <CustomerList
                customers={customers}
                loading={loading}
                page={page}
                setPage={setPage}
                totalPages={totalPages}
                handleDeleteCustomer={handleDeleteCustomer}
                handleEditCustomer={handleEditCustomer}
            />
            <CustomerForm fetchCustomers={fetchCustomers} />
        </div>
    );
}

export default Home;
