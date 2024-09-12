import React from 'react';
import LoginForm from '../components/Auth/LoginForm';

function Login({ setIsAuthenticated }) {
    return (
        <div className="auth-page">
            <LoginForm setIsAuthenticated={setIsAuthenticated} />
        </div>
    );
}

export default Login;
