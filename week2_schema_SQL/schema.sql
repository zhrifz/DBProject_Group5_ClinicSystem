CREATE DATABASE clinic_db;
USE clinic_db;

-- Staff Table
CREATE TABLE Staff (
  staffID INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100),
  phone_no VARCHAR(20),
  email VARCHAR(100)
);

-- Doctor Table
CREATE TABLE Doctor (
  doctorID INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  specialization VARCHAR(100),
  phone VARCHAR(15),
  email VARCHAR(100),
  working_days VARCHAR(50),
  room_no VARCHAR(10)
);

-- Patient Table
CREATE TABLE Patient (
  patientID INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  age INT,
  gender VARCHAR(10),
  date_of_birth DATE,
  phone VARCHAR(15),
  address VARCHAR(255),
  emergency_contact VARCHAR(15)
);

-- Appointment Table
CREATE TABLE Appointment (
  appointmentID INT AUTO_INCREMENT PRIMARY KEY,
  appointment_number VARCHAR(20),
  reason_for_appointment TEXT,
  appointment_time DATETIME,
  status VARCHAR(100),
  patient_come_into_hospital ENUM('yes', 'no'),
  doctor_comment TEXT,
  doctorID INT,
  patientID INT,
  FOREIGN KEY (doctorID) REFERENCES Doctor(doctorID),
  FOREIGN KEY (patientID) REFERENCES Patient(patientID)
);
