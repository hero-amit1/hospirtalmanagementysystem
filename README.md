# Hospital Management System (PHP + MySQL)

A modern, role-based Hospital Management System built with PHP and MySQL for healthcare facilities. Features patient registration, doctor appointment management, prescription handling, and admin controls with a secure, responsive interface.

## Features

### Patient Portal
- User registration and login with password hashing (BCrypt)
- Appointment booking with real-time doctor fee retrieval
- Appointment history and status tracking
- Prescription viewing
- Contact messages

### Doctor Dashboard
- View scheduled appointments
- Cancel appointments
- Create and manage prescriptions
- Access patient information
- Dashboard with appointment statistics

### Admin/Receptionist Panel
- Manage doctor roster (add/delete doctors)
- View all patients and appointments
- Monitor prescriptions
- System overview and statistics
- Doctor specialization management

### Core Functionality
- Secure session management with auto-redirect
- Browser back-button lock (prevents unauthorized access via navigation)
- Appointment status tracking (Active/Cancelled)
- Prescription records with patient history
- Modern, responsive UI with gradient design
- Cache controls and page refresh protection

## Tech Stack

- **Backend**: PHP 7+ with prepared statements (SQL injection prevention)
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 4.3.1, jQuery, Font Awesome icons
- **Fonts**: Manrope, Space Grotesk
- **PDF Generation**: TCPDF
- **Server**: XAMPP (Apache + MySQL)

## Project Structure

```
Hospital-Management-System-master/
├── admin-panel.php              # Patient appointment booking dashboard
├── admin-panel1.php             # Admin/Receptionist control panel
├── doctor-panel.php             # Doctor appointment dashboard
├── prescribe.php                # Prescription creation form
├── index.php                    # Patient login page
├── index1.php                   # Admin login page
├── register-error.php           # Patient registration
├── logout.php / logout1.php     # Session termination
├── myhmsdb.sql                  # Database schema and sample data
├── include/                     # Database config and headers/footers
│   ├── config.php
│   ├── checklogin.php
│   ├── header.php
│   └── footer.php
├── css/                         # Bootstrap and custom stylesheets
├── js/                          # jQuery and custom scripts
├── TCPDF/                       # PDF generation library
└── vendor/                      # Composer dependencies
```

## Installation & Setup

### 1) Prerequisites

- XAMPP (Apache + MySQL)
- Windows system
- Port 80 (Apache) and 3306 (MySQL) available

### 2) Project Placement

Clone/extract to:
```
C:\xampp\htdocs\Hospital-Management-System-master
```

### 3) Database Setup

Create `myhmsdb` database and import schema:

**Option A: phpMyAdmin (GUI)**
1. Open http://localhost/phpmyadmin
2. Create new database: `myhmsdb`
3. Import file: `myhmsdb.sql`

**Option B: Command Line (PowerShell)**
```powershell
Set-Location C:\xampp\htdocs\Hospital-Management-System-master
Get-Content -Raw .\myhmsdb.sql | C:\xampp\mysql\bin\mysql.exe -u root myhmsdb
```

### 4) Database Tables

- `doctb` - Doctor Information (9 doctors: Chris, Steve, Jeff, Shawn, John, Randy, Booker, Kane, Edge)
- `patreg` - Patient Registration
- `appointmenttb` - Appointment Records
- `prestb` - Prescription Records
- `contact` - Contact Messages
- `admintb` - Admin/Receptionist Accounts

### 5) Run Application

Start XAMPP services (Apache + MySQL), then open:
```
http://localhost/Hospital-Management-System-master/index.php
```

## User Access

### Test Credentials

**Patient Login** (index.php)
- Username: user@email.com
- Password: 1234

**Admin/Receptionist Login** (index1.php)
- Username: admin
- Password: admin123

**Create New Patient Account**
- Use registration page to create new patient account

## Security Features

- ✅ Password hashing with BCrypt (password_hash/password_verify)
- ✅ SQL injection prevention via prepared statements
- ✅ Session-based authentication with auto-redirect on unauthorized access
- ✅ CSRF protection via session_regenerate_id()
- ✅ Browser back-button blocking (history.forward() mechanism)
- ✅ Cache-control headers (no-store, no-cache, must-revalidate)
- ✅ Page persistence protection (pageshow event handlers)
- ✅ Session cleanup on logout (all cookies and session data cleared)
- ✅ Role-based access control (different dashboards for patients/doctors/admin)

## Workflow

### Patient Flow
1. Register / Login → Patient Dashboard
2. Book appointment with doctor → Select date/time
3. View appointments and prescriptions
4. Submit contact messages
5. Logout → Redirect to login

### Doctor Flow
1. Login → Doctor Dashboard (appointment list)
2. View scheduled appointments
3. Cancel appointment or Create prescription
4. View all prescriptions issued
5. Logout → Session cleared

### Admin Flow
1. Login → Admin Dashboard (system overview)
2. Manage doctors (add/delete)
3. View patients and appointments
4. Monitor prescriptions
5. View contact messages
6. Logout

## Notes

- System designed for local/college use (XAMPP)
- No external API deployment (MySQL credentials hardcoded for local setup)
- All PHP files validated with PHP linter
- Responsive design works on desktop and tablets
- No JavaScript framework dependencies (vanilla JS + jQuery)

## 4) Default Demo Logins

- Patient: `ram@gmail.com` / `ram123`
- Doctor: `ashok` / `ashok123`
- Admin: `admin` / `admin123`

Note: legacy plaintext credentials from SQL dump are supported and auto-migrated to hashed passwords on successful login.

## 5) Quick Health Checks

### PHP syntax check

```powershell
Set-Location C:\xampp\htdocs\Hospital-Management-System-master
Get-ChildItem -Recurse -File -Include *.php |
  Where-Object { $_.FullName -notmatch "\\vendor\\|\\TCPDF\\" } |
  ForEach-Object { php -l $_.FullName }
```

### Basic HTTP smoke check

```powershell
$base = "http://localhost/Hospital-Management-System-master"
Invoke-WebRequest "$base/index.php" -UseBasicParsing | Select-Object StatusCode
Invoke-WebRequest "$base/index1.php" -UseBasicParsing | Select-Object StatusCode
Invoke-WebRequest "$base/contact.php" -UseBasicParsing | Select-Object StatusCode
```

## 6) Security/Hardening Status

The following were hardened in this workspace:

- DB name consistency to `myhmsdb`
- Prepared statements added to major input-driven endpoints
- Login handlers upgraded to safer auth checks
- Session ID regeneration after successful login
- New doctor passwords stored as hashes
- Duplicate prescription guard for same appointment
- Password output masked in search screens

## 7) Maintenance Notes

- Keep all DB connections pointed to `myhmsdb`
- For new SQL queries, prefer prepared statements
- Avoid rendering sensitive fields (passwords) in UI
- If adding new auth records manually, store passwords as hashes

## 8) Troubleshooting

- `Unknown database 'myhmsdb'`: create/import DB from `myhmsdb.sql`
- `php not recognized`: add `C:\xampp\php` to PATH
- White screen or fatal error: check Apache/PHP logs and run PHP lint command above
# hosiptal
