# 🌸 Florist POS System

A lightweight, web-based **Point of Sale (POS)** system designed for a florist shop. This system enables staff to manage inventory, process transactions, and allows admin users to update stock and monitor sales.

---

## 📁 Project Structure

```
florist_pos/
├── index.php                # Main sales interface for staff
├── admin.php                # Admin dashboard for inventory and sales
├── checkout.php             # Handles checkout and transaction recording
├── login.php                # Admin login page
├── update_cart.php          # Updates item quantity in cart
├── update_stock.php         # Handles admin stock edits
├── export_csv.php           # Exports sales data to CSV
│
├── assets/
│   ├── css/                 # Stylesheets
│   ├── js/                  # Scripts (e.g. button actions)
│   └── images/              # Item images (flowers)
│
├── includes/
│   ├── db.php               # Database connection
│   └── functions.php        # Common helper functions
│
├── sql/
│   └── florist_pos.sql      # SQL schema for database setup
│
└── uploads/                 # Reserved for image uploads
```

---

## 🚀 Features

- 💐 User-friendly POS interface for flower sales
- 🛒 Item cart with quantity controls
- 🔐 Admin login for stock management
- 📊 Exportable transaction history (CSV)
- 🖼️ Image-based item display
- 📦 Real-time stock updates

---

## 🛠️ Installation

1. **Clone or download this repository**
2. **Set up the database:**
   - Import `sql/florist_pos.sql` into your MySQL server using phpMyAdmin or CLI
3. **Configure database connection:**
   - Update `includes/db.php` with your MySQL credentials
4. **Run locally:**
   - Use XAMPP, WAMP, or any PHP + MySQL environment
   - Open `index.php` in browser for staff view
   - Visit `admin.php` to log in as admin

---

## 🔑 Admin Login

- **Username**: `admin`
- **Password**: `admin`  
  (Stored as SHA256 hash in the `admins` table)

---

## 📦 Technologies Used

- PHP
- MySQL
- HTML/CSS
- JavaScript

---

## 📄 License

This project is provided for educational purposes. You may use and adapt it freely.

---

## 🙏 Acknowledgements

This project was developed as part of the Web Technique and Applications course. It serves as a practical implementation of core concepts in web development, including front-end interface design, server-side scripting, database interaction, and basic system functionality. The system was designed to simulate a real-world florist point-of-sale environment for academic and learning purposes.
