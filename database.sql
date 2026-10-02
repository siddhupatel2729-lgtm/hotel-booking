CREATE DATABASE IF NOT EXISTS hotel_booking;
USE hotel_booking;

CREATE TABLE IF NOT EXISTS rooms (
  room_id INT AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(10) NOT NULL UNIQUE,
  room_type VARCHAR(50) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  status VARCHAR(20) DEFAULT 'Available'
);
CREATE TABLE IF NOT EXISTS customers (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  address TEXT
);
CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  customer_id INT NOT NULL,
  booking_date DATE NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  status VARCHAR(20) DEFAULT 'Booked',
  FOREIGN KEY (room_id) REFERENCES rooms(room_id),
  FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

INSERT INTO rooms (room_number, room_type, price, status) VALUES
('101','Single',1500.00,'Available'),
('102','Single',1500.00,'Available'),
('201','Double',2500.00,'Booked'),
('202','Double',2500.00,'Available'),
('301','Deluxe',4000.00,'Booked'),
('401','Suite',7500.00,'Maintenance');
INSERT INTO customers (name, email, phone, address) VALUES
('Amit Sharma','amit@example.com','9876543210','12 MG Road, Surat'),
('Neha Verma','neha@example.com','9123456780','45 Ring Road, Ahmedabad'),
('Rahul Patel','rahul@example.com','9988776655','8 Park Street, Vadodara'),
('Sara Khan','sara@example.com','9012345678','22 Lake View, Mumbai');
INSERT INTO bookings (room_id, customer_id, booking_date, check_in, check_out, status) VALUES
(3,1,CURDATE(),DATE_ADD(CURDATE(),INTERVAL 1 DAY),DATE_ADD(CURDATE(),INTERVAL 4 DAY),'Booked'),
(5,2,CURDATE(),CURDATE(),DATE_ADD(CURDATE(),INTERVAL 2 DAY),'Booked'),
(1,3,DATE_SUB(CURDATE(),INTERVAL 10 DAY),DATE_SUB(CURDATE(),INTERVAL 8 DAY),DATE_SUB(CURDATE(),INTERVAL 6 DAY),'Completed'),
(2,4,DATE_SUB(CURDATE(),INTERVAL 3 DAY),DATE_ADD(CURDATE(),INTERVAL 5 DAY),DATE_ADD(CURDATE(),INTERVAL 7 DAY),'Cancelled'),
(4,1,DATE_SUB(CURDATE(),INTERVAL 20 DAY),DATE_SUB(CURDATE(),INTERVAL 18 DAY),DATE_SUB(CURDATE(),INTERVAL 15 DAY),'Completed');
