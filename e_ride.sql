-- =====================================================
-- E-RIDE DATABASE SCHEMA
-- =====================================================

CREATE DATABASE IF NOT EXISTS e_ride;
USE e_ride;

-- 1. USER
CREATE TABLE users (
    Phone_Number VARCHAR(15) PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Date_of_Birth DATE NOT NULL,
    Gender VARCHAR(20) NOT NULL,
    Area VARCHAR(100) NOT NULL,
    Road VARCHAR(100) NOT NULL,
    Block VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(20) NOT NULL DEFAULT 'Customer',
    Manager_Request_Status VARCHAR(20) NOT NULL DEFAULT 'None',
    Manager_Request_Date DATETIME NULL,
    Manager_Approved_By VARCHAR(15) NULL,
    Manager_Decision_Date DATETIME NULL,
    Created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Manager_Approved_By) REFERENCES users(Phone_Number)
);

-- 2. STATION
CREATE TABLE station (
    Station_ID INT AUTO_INCREMENT PRIMARY KEY,
    Station_Name VARCHAR(100) NOT NULL,
    Area VARCHAR(100) NOT NULL,
    Block VARCHAR(100) NOT NULL,
    Road VARCHAR(100) NOT NULL,
    Capacity INT NOT NULL,
    Available_Cycle_Quantity INT NOT NULL DEFAULT 0,
    Status VARCHAR(20) NOT NULL DEFAULT 'Active',
    Manager_Phone_Number VARCHAR(15) NULL,
    FOREIGN KEY (Manager_Phone_Number) REFERENCES users(Phone_Number)
);

-- 3. VEHICLE
CREATE TABLE vehicle (
    Vehicle_ID INT AUTO_INCREMENT PRIMARY KEY,
    Vehicle_Code VARCHAR(30) UNIQUE NOT NULL,
    Vehicle_Name VARCHAR(100) NOT NULL,
    Vehicle_Type VARCHAR(50) NOT NULL,
    Battery_Backup VARCHAR(50) NOT NULL,
    Top_Speed DECIMAL(5,2) NOT NULL,
    Status VARCHAR(20) NOT NULL DEFAULT 'Available',
    Station_ID INT NULL,
    FOREIGN KEY (Station_ID) REFERENCES station(Station_ID)
);

-- 3B. BIKE_TYPE (predefined bike groups/types, each with its own catalog image)
CREATE TABLE bike_type (
    Bike_Type_ID INT AUTO_INCREMENT PRIMARY KEY,
    Type_Name VARCHAR(50) UNIQUE NOT NULL,
    Bike_Name VARCHAR(100) NOT NULL,
    Battery_Backup VARCHAR(50) NOT NULL,
    Top_Speed DECIMAL(5,2) NOT NULL,
    Image_Path VARCHAR(255) NOT NULL DEFAULT 'assets/images/bikes/default-bike.jpg'
);

-- 4. BOOKING
CREATE TABLE booking (
    Booking_ID INT AUTO_INCREMENT PRIMARY KEY,
    Booking_Date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Status VARCHAR(20) NOT NULL DEFAULT 'Confirmed',
    Phone_Number VARCHAR(15) NOT NULL,
    Start_Station_ID INT NOT NULL,
    FOREIGN KEY (Phone_Number) REFERENCES users(Phone_Number),
    FOREIGN KEY (Start_Station_ID) REFERENCES station(Station_ID)
);

-- 5. BOOKING_VEHICLE
CREATE TABLE booking_vehicle (
    Booking_ID INT NOT NULL,
    Vehicle_ID INT NOT NULL,
    PRIMARY KEY (Booking_ID, Vehicle_ID),
    FOREIGN KEY (Booking_ID) REFERENCES booking(Booking_ID),
    FOREIGN KEY (Vehicle_ID) REFERENCES vehicle(Vehicle_ID)
);

-- 6. RIDE
CREATE TABLE ride (
    Ride_ID INT AUTO_INCREMENT PRIMARY KEY,
    Booking_ID INT NOT NULL,
    Status VARCHAR(20) NOT NULL DEFAULT 'Ongoing',
    Fare DECIMAL(10,2) NOT NULL DEFAULT 0,
    Start_Time DATETIME NOT NULL,
    End_Time DATETIME NULL,
    Start_Station_ID INT NOT NULL,
    End_Station_ID INT NULL,
    Phone_Number VARCHAR(15) NOT NULL,
    FOREIGN KEY (Booking_ID) REFERENCES booking(Booking_ID),
    FOREIGN KEY (Start_Station_ID) REFERENCES station(Station_ID),
    FOREIGN KEY (End_Station_ID) REFERENCES station(Station_ID),
    FOREIGN KEY (Phone_Number) REFERENCES users(Phone_Number)
);

-- 7. RIDE_VEHICLE
CREATE TABLE ride_vehicle (
    Ride_ID INT NOT NULL,
    Vehicle_ID INT NOT NULL,
    PRIMARY KEY (Ride_ID, Vehicle_ID),
    FOREIGN KEY (Ride_ID) REFERENCES ride(Ride_ID),
    FOREIGN KEY (Vehicle_ID) REFERENCES vehicle(Vehicle_ID)
);

-- 8. PAYMENT
CREATE TABLE payment (
    Payment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Amount DECIMAL(10,2) NOT NULL,
    Payment_Date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Payment_Method VARCHAR(30) NOT NULL,
    Payment_Status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    Ride_ID INT NOT NULL,
    Transaction_ID VARCHAR(100) NULL,
    FOREIGN KEY (Ride_ID) REFERENCES ride(Ride_ID)
);

-- =====================================================
-- SEED DATA
-- =====================================================

-- Default Admin account (Phone: 01700000000 / Password: password)
-- Password hash generated with PHP password_hash('password', PASSWORD_DEFAULT)
INSERT INTO users (Phone_Number, Name, Date_of_Birth, Gender, Area, Road, Block, Password, Role, Manager_Request_Status)
VALUES ('01700000000', 'System Admin', '1990-01-01', 'Other', 'Dhaka', 'Road 1', 'Block A', '$2y$10$KQqD3UJc/NN4PCfJJ7CihuYu4dFPOPtjlCQ2.yR38ZsHU2rbhk9Ia', 'Admin', 'None');
-- Change this password after first login.

-- Sample stations
INSERT INTO station (Station_Name, Area, Block, Road, Capacity, Available_Cycle_Quantity, Status)
VALUES ('Dhanmondi Station', 'Dhanmondi', 'Block A', 'Road 5', 20, 0, 'Active'),
       ('Gulshan Station', 'Gulshan', 'Block 2', 'Road 11', 15, 0, 'Active');

-- 5 predefined bike types/groups (image paths are placeholders - drop the real
-- photos into assets/images/bikes/ using these same file names)
INSERT INTO bike_type (Type_Name, Bike_Name, Battery_Backup, Top_Speed, Image_Path)
VALUES ('E-Ride City', 'City Cruiser', '40 km range', 25.00, 'assets/images/bikes/bike1.jpg'),
       ('E-Ride Sport', 'Sport Rider', '35 km range', 35.00, 'assets/images/bikes/bike2.jpg'),
       ('E-Ride Mountain', 'Trail Blazer', '30 km range', 30.00, 'assets/images/bikes/bike3.jpg'),
       ('E-Ride Mini', 'Mini Commuter', '25 km range', 20.00, 'assets/images/bikes/bike4.jpg'),
       ('E-Ride Cargo', 'Cargo Hauler', '45 km range', 22.00, 'assets/images/bikes/bike5.jpg');
