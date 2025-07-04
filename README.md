# Florist POS System

A lightweight Point of Sale (POS) web application designed for florist shops. Built using HTML, CSS, JavaScript, PHP, and MySQL.

## 🌸 Features

### 👥 User Roles
- **Staff View**
  - Browse 6 predefined items for sale
  - Add/remove items to cart using plus/minus buttons
  - Cart updates live and shows stock level
  - Checkout button triggers:
    - Stock update
    - Transaction recording
    - A receipt preview popup with `OK`, `Download`, and `Print` buttons

- **Admin View**
  - Accessed via login modal from staff view
  - Edit item name, price, stock, and image (file upload)
  - Deactivate or activate items (hidden from staff but retained for record)
  - Add new item (requires all fields)
  - Export transaction records as CSV

### 🧾 Receipt System
- Receipt preview appears in a popup after checkout
- Receipt includes:
  - Shop name
  - Date & time (Malaysia timezone)
  - List of items, prices, and total
  - Thank you message
- Buttons:
  - **OK**: close popup
  - **Download**: download `.txt` file (e.g., `receipt_5.txt`)
  - **Print**: open system print dialog with thermal-style formatting

### 💾 Database Schema
- `items`: products with name, image path, price, stock, and status
- `transactions`: stores each checkout
- `transaction_items`: itemized breakdown of each transaction
- `admins`: admin credentials

## 🚀 Setup Instructions

1. **Requirements**
   - PHP 7.x or 8.x
   - MySQL
   - Apache (XAMPP/Laragon/etc.)
   - Browser (Chrome recommended)

2. **Database**
   - Import `florist_pos.sql` into MySQL
   - Default admin login:
     - **Username**: `admin`
     - **Password**: `admin`

3. **File Setup**
   - Place project folder (`florist_pos`) in your web server root (e.g., `htdocs`)
   - Ensure `uploads/` folder is writable for image uploads
   - Access `http://localhost/florist_pos/index.php`

## 📁 Folder Structure

```
florist_pos/
├── admin.php
├── checkout.php
├── export_csv.php
├── index.php
├── login.php
├── update_cart.php
├── update_stock.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── includes/
│   └── functions.php
├── uploads/           ← where uploaded item images go
├── receipts/          ← stores generated receipt text files
└── florist_pos.sql    ← MySQL schema and sample data
```

## 📦 Technologies Used
- HTML5/CSS3/JavaScript
- PHP (Procedural)
- MySQL
- File-based image and receipt handling

## 🛠️ To Do
- Transaction filter by date
- UI theme switching
- Customer info capture (name, phone)

## 🔐 Security Notes
- No password hashing in UI; `admins.password_hash` is hashed via `SHA2`
- No CSRF/XSS protection yet — for learning purposes

---

🌼 *Thank you for using the Florist POS system!* 🌼
