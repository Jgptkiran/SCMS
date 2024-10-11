-- Create the database
CREATE DATABASE ansoles;

-- Use the database
USE ansoles;

-- Create the facilities table
CREATE TABLE facilities (
    FacilityID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Description TEXT,
    HourlyRate DECIMAL(10, 2) NOT NULL,
    Image VARCHAR(255)
);

-- Create the customers table
CREATE TABLE customers (
    customerId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    contactNumber VARCHAR(20),
    address VARCHAR(255),
    Password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Create the bookings table
CREATE TABLE bookings (
    bookingId INT AUTO_INCREMENT PRIMARY KEY,
    customerId INT NOT NULL,
    date DATE NOT NULL,
    slots INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FacilityID INT NOT NULL,
    FOREIGN KEY (customerId) REFERENCES customers(customerId),
    FOREIGN KEY (FacilityID) REFERENCES facilities(FacilityID)
);

-- Create the admins table
CREATE TABLE admins (
    admin_Id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL
);


