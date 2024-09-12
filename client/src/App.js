import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import { getUser } from './services/api';
import Login from './pages/Login';
import Home from './pages/Home';
import Register from './pages/Register';
import './styles/Common.css';

function App() {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [loading, setLoading] = useState(true);
    useEffect(() => {
        const authenticateUser = async () => {
            try {
                const user = await getUser();
                if (user) {
                    setIsAuthenticated(true);
                }
            } catch (error) {
                console.log('Authentication failed or user not logged in');
            } finally {
                setLoading(false);
            }
        };
        authenticateUser();
    }, []);
    // Loader
    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <Router>
            <Routes>
                {/* Public routes */}
                <Route
                    path="/login"
                    element={isAuthenticated ? <Navigate to="/" /> : <Login setIsAuthenticated={setIsAuthenticated} />}
                />
                <Route path="/register" element={isAuthenticated ? <Navigate to="/" /> : <Register />} />
                {/* Protected routes */}
                <Route path="/" element={isAuthenticated ? <Home /> : <Navigate to="/login" />} />
            </Routes>
        </Router>
    );
}

export default App;
