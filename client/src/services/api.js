import axios from 'axios';

// Create axios instance
const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

// Automatically add the Bearer token to all requests
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Authentication endpoints
export const login = (credentials) => api.post('/auth/login', credentials);
export const register = (userData) => api.post('/auth/register', userData);
export const getUser = () => {
    return api.get('/auth/user');
};

// Customer endpoints
export const getCustomers = (page, perPage) => api.get(`/customers?page=${page}&perPage=${perPage}`);
export const addCustomer = (customerData) => api.post('/customers', customerData);
export const deleteCustomer = (customerId) => api.delete(`/customers?id=${customerId}`);
export const updateCustomer = (customerData) => api.patch(`/customers`, customerData);
