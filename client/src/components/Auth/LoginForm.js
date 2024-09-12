import React, { useState } from 'react';
import { login } from '../../services/api';
import { Link } from 'react-router-dom';
import '../../styles/Auth.css';

function LoginForm({ setIsAuthenticated }) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    // Handle form submit for login
    const handleLogin = async (e) => {
        e.preventDefault();
        setError('');
        try {
            const response = await login({ email, password });
            if (response.data.token) {
                localStorage.setItem('token', response.data.token);
                setIsAuthenticated(true);
            }
        } catch {
            setError('Invalid email or password');
        }
    };

    return (
        <div className="auth-container">
            <h2>Login</h2>
            {/* Display error message if there's any */}
            {error && <p className="errorMessage">{error}</p>}
            <form onSubmit={handleLogin} className="form">
                <div className="formGroup">
                    <label>Email:</label>
                    <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required className="input" />
                </div>
                <div className="formGroup">
                    <label>Password:</label>
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                        className="input"
                    />
                </div>
                <button type="submit" className="submitButton">
                    Login
                </button>
            </form>
            <p>
                Don't have an account? <Link to="/register">Register here</Link>
            </p>
        </div>
    );
}

export default LoginForm;
