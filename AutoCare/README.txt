AUTOCARE - LOCAL SETUP

1. Extract the AutoCare folder into:
   C:\xampp\htdocs\PHPMYSQL\AutoCare

2. Start Apache and MySQL from XAMPP.

3. Check config/database.php:
   Host: localhost
   Username: root
   Password: (empty by default in XAMPP)
   Database: autocare_db

4. The PHP pages in this package are aligned with the database structure
   shown in the supplied phpMyAdmin screenshots:
   - vehicles: brand, model, year, plate_number
   - services: service_name, description, price, duration
   - appointments: appointment_date, appointment_time, status, notes

5. If this is a fresh installation, import database.sql in phpMyAdmin.
   If you already have data in autocare_db, BACK UP the database first.
   Do not import a fresh schema over an existing database without checking it.

6. For the first administrator only, open:
   http://localhost/PHPMYSQL/AutoCare/setup_admin.php
   Create the admin account, then delete setup_admin.php from the project.

7. Open:
   http://localhost/PHPMYSQL/AutoCare/login.php

CUSTOMER FEATURES
- Create a customer account
- Register and remove vehicles
- Request service appointments
- View appointment history and status

ADMIN FEATURES
- Dashboard statistics and recent appointments
- Customer list
- Add, edit and remove vehicles
- Add, edit and manage services
- Manage mechanics
- Review appointments, assign mechanics and update status

IMPORTANT
The database structure may vary between installations. The current PHP pages
expect the columns listed in step 4, plus the users and mechanics columns used
by the application. Keep a database backup before making schema changes.
