# SDK Contracting & Services W.L.L. — Official Website

**Qatar-based construction and services company website**

---

## 🏗️ About

Professional business website for **SDK Contracting & Services W.L.L.** (Reg. No. 207895), Doha, Qatar.

Built with PHP, MySQL, and vanilla CSS/JS — no frameworks required.

---

## 📁 Project Structure

```
public_html/
├── index.php              # Home page
├── about.php              # About & team
├── services.php           # All services
├── projects.php           # Project portfolio
├── contact.php            # Contact form
├── admin/                 # Admin panel (secure)
│   ├── login.php
│   ├── dashboard.php
│   ├── add-project.php
│   ├── edit-project.php
│   ├── add-service.php
│   ├── edit-service.php
│   ├── view-messages.php
│   ├── home-settings.php
│   ├── whatsapp-settings.php
│   ├── change-password.php
│   ├── forgot-password.php
│   └── reset-password.php
├── includes/
│   ├── db.php             # ⚠️ NOT in GitHub (add credentials)
│   ├── auth.php
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/style.css
│   └── js/main.js
├── uploads/               # ⚠️ NOT in GitHub (user uploads)
│   ├── projects/
│   └── services/
└── sql/
    └── database.sql       # Database schema
```

---

## 🚀 Setup

### 1. Clone the repo
```bash
git clone https://github.com/YOUR-USERNAME/sdk-construction-website.git
```

### 2. Create database
Import `sql/database.sql` into your MySQL database.

### 3. Configure database
Copy and fill in credentials:
```bash
cp includes/db.example.php includes/db.php
```

Edit `includes/db.php`:
```php
$dbname = 'your_database_name';
$user   = 'your_username';
$pass   = 'your_password';
```

### 4. Set folder permissions
```bash
chmod 755 uploads/
chmod 755 uploads/projects/
chmod 755 uploads/services/
```

### 5. Access the site
- Website: `https://yourdomain.com/`
- Admin: `https://yourdomain.com/admin/login.php`

---

## 🔐 Admin Login

```
Username: admin
Password: Admin@1234  ← Change immediately after first login!
```

---

## 🛠️ Tech Stack

| Technology | Usage |
|---|---|
| PHP 8.x | Backend |
| MySQL | Database |
| PDO | DB Connection |
| HTML/CSS | Frontend |
| Vanilla JS | Interactions |
| Hostinger | Hosting |

---

## 📞 Company Info

- **Company:** SDK Contracting & Services W.L.L.
- **Reg. No:** 207895
- **Location:** Al Gassarat Road, Street No. 11, Industrial Area, Doha, Qatar
- **Phone:** +974 6692 7592 / +974 7702 4499
- **Email:** sherdilkhan@sdk.com / ajmalkhan@sdk.com

---

## 👨‍💻 Developed By

**Chahat & Saeed** — Web Developers, Qatar

---

*© 2025 SDK Contracting & Services W.L.L. All rights reserved.*
