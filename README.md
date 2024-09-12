# CRM Application

This is a simple CRM (Customer Relationship Management) app with a PHP backend (API) and a React frontend (Client). The API uses MySQL for the database and JWT for authentication. The project is designed to run locally using XAMPP for the backend and React development server for the frontend.

## Table of Contents

-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Database Setup](#database-setup)
-   [Environment Variables](#environment-variables)
-   [Running the Application](#running-the-application)

## Requirements

-   **XAMPP** (Apache, MySQL, PHP, and Composer support)
-   **Node.js** (for running the React frontend)
-   **Composer** (PHP dependency management)

## Installation

### Step 1: Clone the Repository

First, navigate to the `htdocs` folder in your XAMPP installation, then clone the repository.

```bash
cd C:/xampp/htdocs
git clone https://github.com/dimafili2412/mackeno_assignment
```

This will clone the repository into a folder called crm inside the htdocs directory.

### Step 2: Install Backend (API) Dependencies

```bash
cd crm/api
composer install
```

This will install all the required PHP packages for the backend.

### Step 3: Install Frontend (Client) Dependencies

```bash
cd ../client
npm install
```

This will install all the necessary React dependencies for the frontend.

## Database Setup

Open phpMyAdmin or any MySQL client and run the following SQL script to create the database and the necessary tables.

```sql
CREATE DATABASE crm_api;

USE crm_api;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Customers Table
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT,
    phone_number VARCHAR(15)
);
```

## Environment Variables

Both the frontend and backend require environment variables to configure the application. The necessary .env files for both the client and server must be created.

## Backend (API) .env

In the /api directory, create a file named .env with the following content:
ALLOW_ORIGIN=http://localhost:3000
DB_HOST=localhost
DB_NAME=crm_api
DB_USERNAME=root
DB_PASSWORD=
JWT_SECRET=123

## Frontend (Client) .env

In the /client directory, create a file named .env with the following content:
REACT_APP_API_URL=http://localhost/mackeno_assignment/api

### Running the Application

## Step 1: Start Apache and MySQL in XAMPP

1. Open the XAMPP Control Panel.
2. Start both Apache and MySQL.

## Step 2: Running the PHP Backend

Make sure Apache is running in XAMPP. The backend API will be accessible at http://localhost/mackeno_assignment/api once you set up the Apache alias (see below).

## Step 3: Running the React Frontend

To run the React frontend, open a terminal and navigate to the /client directory:

```bash
cd crm/client
npm start
```

This will start the React development server at http://localhost:3000.

## Step 4: Access the Application

The API will be accessible at http://localhost/crm/api.
The React frontend will be available at http://localhost:3000.
