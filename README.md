# 🎓 EduMantra ERP — College Management System

A complete **College ERP (Enterprise Resource Planning)** system built with **PHP, MySQL & Bootstrap**.  
Manage Students, Faculty, Departments, Courses, and Assignments — all from one unified platform.

---

## 🌐 Live Preview

> Run locally using **XAMPP** (see setup instructions below)

---

## 📸 Screenshots

| Homepage | Admin Dashboard | Student Dashboard |
|----------|----------------|-------------------|
| Login portal with 3 roles | Manage all records | View assignments & profile |

---

## ✨ Features

### 👨‍💼 Admin Panel
- Secure login with password hashing (`password_hash`)
- Register new admin account
- Add / Edit / Delete **Departments**
- Add / Edit / Delete **Courses**
- Add / Edit / Delete **Students**
- Add / Edit / Delete **Faculty**
- Manage Student & Faculty **Active/Inactive Status**
- Change admin password

### 👨‍🎓 Student Panel
- Secure login (only active students can login)
- View personal profile (name, email, course, department)
- View assigned assignments
- Submit assignments (PDF upload)
- View & delete submitted assignments
- Change password
- Forgot password recovery

### 👨‍🏫 Faculty Panel
- Secure login (only active faculty can login)
- View faculty dashboard
- Create assignments (title, subject, due date, description)
- View student submissions
- Delete assignments & submissions
- Change password
- Forgot password recovery

---

## 🛠️ Tech Stack

| Technology | Usage |
|------------|-------|
| **PHP 8.x** | Backend logic |
| **MySQL / MariaDB** | Database |
| **Bootstrap 5.3** | Frontend UI |
| **Bootstrap Icons** | Social & UI icons |
| **XAMPP** | Local development server |
| **phpMyAdmin** | Database management |

---

## 📁 Project Structure

```
Edumantra-ERP/
│
├── index.html                  # Homepage (Student / Faculty / Admin login links)
├── .gitignore                  # Git ignore rules
│
├── config/
│   └── db.php                  # Database connection
│
├── admin/
│   ├── login.php               # Admin login
│   ├── register.php            # Admin registration
│   ├── dashboard.php           # Admin dashboard (all management)
│   ├── insert_*.php            # Add records
│   ├── edit_*.php              # Edit forms
│   ├── update_*.php            # Update records
│   ├── delete_*.php            # Delete records
│   ├── update_admin_password.php
│   ├── set_admin_password.php
│   └── logout.php
│
├── students/
│   ├── student_login.php       # Student login
│   ├── student_dashboard.php   # Student panel
│   ├── submit_assignment_backend.php
│   ├── delete_submission.php
│   ├── forgot_password.php
│   ├── update_password.php
│   ├── student_logout.php
│   └── uploads/               # Uploaded assignment PDFs
│
├── faculty/
│   ├── faculty_login.php       # Faculty login
│   ├── faculty_dashboard.php   # Faculty panel
│   ├── insert_assignment.php
│   ├── delete_assignment.php
│   ├── delete_submission_faculty.php
│   ├── faculty_forgot_password.php
│   ├── update_password.php
│   └── faculty_logout.php
│
└── Database/
    └── college_db.sql          # Full database schema & structure
```

---

## ⚙️ Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed (Apache + MySQL)
- PHP 8.x
- A browser

---

### Step 1 — Clone the Repository

```bash
git clone https://github.com/techsaurabh08/Edumantra-ERP.git
```

---

### Step 2 — Move to XAMPP htdocs

Copy the project folder into `C:/xampp/htdocs/`.  
*Note: If your local folder name is `collegemg erp` or `Edumantra-ERP`, use that exact name.*

Path:
```
C:/xampp/htdocs/collegemg erp/
```
*(or `C:/xampp/htdocs/Edumantra-ERP/` if cloned/renamed)*

---

### Step 3 — Start XAMPP

Open **XAMPP Control Panel** and start:
- **Apache**
- **MySQL**

---

### Step 4 — Import Database

1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click **"New"** → Create database named: `college_db`
3. Click **Import** tab
4. Choose file: `Database/college_db.sql`
5. Click **Go**

---

### Step 5 — Configure Database (if needed)

Open `config/db.php` and update if your settings are different:

```php
$host = "127.0.0.1";
$user = "root";
$pass = "";           // your MySQL password
$db   = "college_db";
$port = 3307;         // set to 3307 if your XAMPP MySQL uses 3307
```

---

### Step 6 — Register Admin Account

Go to:
```
http://localhost/collegemg%20erp/admin/register.php
```
*(or `http://localhost/Edumantra-ERP/admin/register.php` if folder is renamed to Edumantra-ERP)*

Create your admin username and password.

---

### Step 7 — Run the Project 🚀

Open your browser and visit:
```
http://localhost/collegemg%20erp/index.html
```
*(or `http://localhost/Edumantra-ERP/index.html` if folder is renamed to Edumantra-ERP)*

---

## 🔐 Login Roles

| Role | URL | Notes |
|------|-----|-------|
| **Admin** | `/admin/login.php` | Register first at `/admin/register.php` |
| **Student** | `/students/student_login.php` | Admin must set status = Active |
| **Faculty** | `/faculty/faculty_login.php` | Admin must set status = Active |

> ⚠️ **Important:** Newly added students and faculty have **Inactive** status by default.  
> Admin must go to **Status Management** to activate them before they can log in.

---

## 🗄️ Database Tables

| Table | Description |
|-------|-------------|
| `admin` | Admin login credentials |
| `students` | Student records |
| `faculty` | Faculty records |
| `courses` | Course list |
| `departments` | Department list |
| `assignments` | Assignments created by faculty |
| `submitted_assignments` | Files submitted by students |

---

## 🔒 Security Features

- Passwords stored using `password_hash()` (bcrypt)
- Login verified using `password_verify()`
- SQL Injection protection via
`mysqli_real_escape_string()`
- Session-based authentication for all 3 roles
- Inactive users cannot login
- Unauthorized page access redirects to login

---

## 📋 Known Limitations

- No email integration for forgot password (manual reset via admin)
- No role-based assignment filtering by course (only by department)
- No marks / grading system yet

---

## 🚀 Future Improvements

- [ ] Email-based password reset (PHPMailer)
- [ ] Student marks & grade management
- [ ] Attendance tracking module
- [ ] Timetable management
- [ ] Notifications system
- [ ] Mobile responsive improvements

---

## 👨‍💻 Developer

**Saurabh Tiwari**  
📍 Patna, Bihar, India  
📞 +91 7667901214  
✉️ info@mycollege.edu  

🔗 GitHub: [@techsaurabh08](https://github.com/techsaurabh08)

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

> ⭐ If you found this project helpful, please give it a **star** on GitHub!
