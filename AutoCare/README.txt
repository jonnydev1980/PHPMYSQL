AUTOCARE – UDHËZUESI I INSTALIMIT

1. Nxirreni folderin AutoCare në:
   C:\xampp\htdocs\PHPMYSQL\AutoCare

2. Hapeni XAMPP dhe aktivizoni Apache dhe MySQL.

3. Hapeni phpMyAdmin:
   http://localhost/phpmyadmin

4. Importoni database.sql. Emri i databazës është autocare_db.
   Nëse databaza ekziston dhe ka të dhëna, bëni backup para importimit.
   Skema përdor kolonën specialty te mechanics dhe statuset active/inactive.

5. Kontrolloni config/database.php:
   Host: localhost
   Username: root
   Password: bosh (si konfigurim standard i XAMPP)
   Database: autocare_db

6. Krijoni administratorin duke hapur:
   http://localhost/PHPMYSQL/AutoCare/setup_admin.php
   Plotësoni emrin, emailin dhe fjalëkalimin.
   Pas krijimit të administratorit, fshini setup_admin.php nga serveri.

7. Hapeni faqen kryesore:
   http://localhost/PHPMYSQL/AutoCare/

FAQJA PUBLIKE
- Prezantimi i AutoCare dhe informatat rreth kompanisë
- Lista e shërbimeve
- Pse të përdorni platformën
- Lidhjet për kyçje dhe regjistrim

KLIENTI
- Krijon llogari dhe kyçet në sistem
- Regjistron automjetet e veta
- Kërkon termin për një shërbim
- Përcjell statusin e termineve

ADMINISTRATORI
- Sheh statistikat dhe terminet e fundit
- Menaxhon klientët dhe automjetet
- Shton, ndryshon dhe fshin shërbimet
- Menaxhon mekanikët
- Konfirmon terminet, cakton mekanikët dhe përditëson statuset

SHËNIM
Faqja publike përdor fotografi nga Unsplash dhe për shfaqjen e tyre kërkohet lidhje interneti.
Çmimet fillestare të shërbimeve janë shembull dhe mund të ndryshohen nga administratori.
