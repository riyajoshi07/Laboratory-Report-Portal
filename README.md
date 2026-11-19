# Laboratory Report Portal

A PHP + MySQL academic project prototype with role-based dashboards for Admin, Doctor, Lab Technician and Patient.

## Requirements
- Windows / macOS / Linux
- XAMPP (Apache + MySQL) or equivalent PHP 8+ and MySQL/MariaDB
- Browser

## Run locally with XAMPP
1. Install and open XAMPP.
2. Start **Apache** and **MySQL**.
3. Copy this folder into `C:\\xampp\\htdocs\\laboratory-report-portal` (Windows).
4. Open `config.php`. The default XAMPP database settings are `localhost`, database `laboratory_portal`, user `root`, blank password. Adjust if needed.
5. Visit `http://localhost/laboratory-report-portal/setup.php` once. It creates the database, tables and demo accounts.
6. After successful setup, delete `setup.php` from the project folder.
7. Open `http://localhost/laboratory-report-portal/` and sign in.

## Demo accounts
| Role | Email | Password |
|---|---|---|
| Admin | admin@labportal.test | Admin@123 |
| Doctor | doctor@labportal.test | Doctor@123 |
| Technician | tech@labportal.test | Tech@123 |
| Patient | patient@labportal.test | Patient@123 |

Change demo passwords before using outside a local classroom demo.

## Features
- Admin: create doctor/technician accounts, create test categories and laboratory tests, view request/report summary.
- Doctor: request tests for registered patients and view their patients' report statuses/downloads.
- Technician: view worklist, update status, upload PDF reports (5 MB maximum).
- Patient: view their own report list and download their PDF reports.
- Password hashing, prepared SQL statements, session login, CSRF tokens, role checks and ownership checks for report downloads.

## Database tables
`users`, `patients`, `doctors`, `technicians`, `test_categories`, `laboratory_tests`, `test_requests`, `test_reports`.

The five required entities are present, with supporting tables for login, categories and test requests.

## GitHub upload
1. Create a new **public or private** repository on GitHub, e.g. `laboratory-report-portal`.
2. Do not upload real patient data or actual medical reports. Keep `uploads/` empty.
3. In VS Code, open this project folder.
4. Open Terminal and run:
   ```bash
   git init
   git add .
   git commit -m "Initial Laboratory Report Portal project"
   git branch -M main
   git remote add origin https://github.com/YOUR-USERNAME/laboratory-report-portal.git
   git push -u origin main
   ```
5. Replace `YOUR-USERNAME` with your GitHub username. Complete GitHub browser authentication if prompted.

## Important
This is an educational prototype, not a production clinical system. Before real use, add HTTPS, secure deployment configuration, audit logs, backups, stronger authorization review, malware scanning, retention policies, and applicable privacy/security compliance. Never use real patient information in a public repository.
