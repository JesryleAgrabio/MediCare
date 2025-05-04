CREATE DATABASE JEEPS;
USE JEEPS;



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    security_question VARCHAR(50) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    account_type ENUM('user', 'admin', 'jeep moderator') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




CREATE TABLE Route (
    routeId INT PRIMARY KEY AUTO_INCREMENT,
    routeName VARCHAR(100) NOT NULL,
    startLocation VARCHAR(100) NOT NULL,
    endLocation VARCHAR(100) NOT NULL,
    distanceKm DOUBLE,
    fare DOUBLE,
    estimatedTime DOUBLE,
    stops TEXT
);


CREATE TABLE Driver (
    driverId INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
   
);

CREATE TABLE PUV (
    puvId INT PRIMARY KEY AUTO_INCREMENT,
    plateNumber VARCHAR(20) NOT NULL UNIQUE,
    routeId INT,
    driverId INT,
    FOREIGN KEY (routeId) REFERENCES Route(routeId) ON DELETE SET NULL,
    FOREIGN KEY (driverId) REFERENCES Driver(driverId) ON DELETE SET NULL
);

CREATE TABLE Trip (
    tripId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT,
    puvId INT,
    farePaid DOUBLE,
    tripStatus VARCHAR(50),
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (puvId) REFERENCES PUV(puvId) ON DELETE SET NULL,
);

CREATE TABLE TripPlanned (
    planId INT PRIMARY KEY AUTO_INCREMENT,
    routeId INT,
    tripId INT,
    FOREIGN KEY (routeId) REFERENCES Route(routeId) ON DELETE SET NULL,
    FOREIGN KEY (tripId) REFERENCES Trip(tripId) ON DELETE SET NULL
);


CREATE TABLE bus_stops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);