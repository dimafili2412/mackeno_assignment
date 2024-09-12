import React, { useState } from 'react';
import { register } from '../../services/api';
import { useNavigate } from 'react-router-dom';
import '../../styles/Auth.css';

function RegisterForm() {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [error, setError] = useState('');
    const [validationErrors, setValidationErrors] = useState([]);
    const [successMessage, setSuccessMessage] = useState('');
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();

    // Handle form submission
    const handleRegister = async (e) => {
        e.preventDefault();
        setError('');
        setValidationErrors([]);
        if (password !== confirmPassword) {
            setError('Passwords do not match');
            return;
        }
        setLoading(true);
        try {
            const response = await register({ name, email, password });
            setSuccessMessage('Registration successful! Redirecting to login...');
            navigate('/login');
        } catch (err) {
            if (err.response && err.response.data.errors) {
                setValidationErrors(err.response.data.errors);
            } else {
                setError('Failed to register. Please try again.');
            }
        } finally {
            setLoading(false);
        }
    };

    const redirectToLogin = () => {
        navigate('/login'); // Redirect to login page when the button is clicked
    };

    return (
        <div className="auth-container">
            <h2>Register</h2>
            {/* General error message */}
            {error && <p className="errorMessage">{error}</p>}
            {/* List of field-specific validation errors */}
            {validationErrors.length > 0 && (
                <ul className="errorList">
                    {validationErrors.map((errMsg, index) => (
                        <li key={index} className="errorMessage">
                            {errMsg}
                        </li>
                    ))}
                </ul>
            )}
            {/* Success message */}
            {successMessage && <p className="successMessage">{successMessage}</p>}
            {/* Registration Form */}
            <form onSubmit={handleRegister} className="form">
                <div className="formGroup">
                    <label>Name:</label>
                    <input type="text" value={name} onChange={(e) => setName(e.target.value)} required className="input" />
                </div>
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
                <div className="formGroup">
                    <label>Confirm Password:</label>
                    <input
                        type="password"
                        value={confirmPassword}
                        onChange={(e) => setConfirmPassword(e.target.value)}
                        required
                        className="input"
                    />
                </div>
                <button type="submit" className="submitButton" disabled={loading}>
                    {loading ? 'Registering...' : 'Register'}
                </button>
            </form>
            {/* Button to redirect to login page */}
            <div className="redirect-container">
                <button onClick={redirectToLogin} className="redirectButton">
                    Go to Login
                </button>
            </div>
        </div>
    );
}

export default RegisterForm;
