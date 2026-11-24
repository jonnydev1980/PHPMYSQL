CREATE DATABASE IF NOT EXISTS autocare_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE autocare_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mechanics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(160) NULL,
    specialty VARCHAR(120) NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NULL,
    plate_number VARCHAR(30) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    CONSTRAINT fk_vehicles_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    service_id INT NOT NULL,
    mechanic_id INT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending','confirmed','in_progress','completed','cancelled')
        NOT NULL DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    INDEX (vehicle_id),
    INDEX (service_id),
    INDEX (mechanic_id),
    CONSTRAINT fk_appointments_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_vehicle
        FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_service
        FOREIGN KEY (service_id) REFERENCES services(id),
    CONSTRAINT fk_appointments_mechanic
        FOREIGN KEY (mechanic_id) REFERENCES mechanics(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Create the first administrator with setup_admin.php.
-- Delete setup_admin.php from the server after creating the administrator.


-- Sherbimet fillestare. Mund te ndryshohen nga paneli i administratorit.
INSERT INTO services (service_name, description, price, duration)
SELECT 'Diagnostikim kompjuterik', 'Kontroll i sistemeve elektronike dhe identifikim i defekteve.', 20.00, 30
WHERE NOT EXISTS (SELECT 1 FROM services WHERE service_name = 'Diagnostikim kompjuterik');

INSERT INTO services (service_name, description, price, duration)
SELECT 'Servisim periodik', 'Ndërrim i vajit dhe filtrave, si dhe kontroll bazë i automjetit.', 35.00, 60
WHERE NOT EXISTS (SELECT 1 FROM services WHERE service_name = 'Servisim periodik');

INSERT INTO services (service_name, description, price, duration)
SELECT 'Kontroll i sistemit të frenimit', 'Kontroll i frenave, disqeve dhe elementeve përkatëse.', 25.00, 45
WHERE NOT EXISTS (SELECT 1 FROM services WHERE service_name = 'Kontroll i sistemit të frenimit');

INSERT INTO services (service_name, description, price, duration)
SELECT 'Kontroll i motorit', 'Kontroll i përgjithshëm i gjendjes dhe funksionimit të motorit.', 30.00, 45
WHERE NOT EXISTS (SELECT 1 FROM services WHERE service_name = 'Kontroll i motorit');
