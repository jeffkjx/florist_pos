# 🌸 Florist POS System

A lightweight, web-based **Point of Sale (POS)** system designed for a florist shop. This system enables staff to manage inventory, process transactions, and allows admin users to update stock, monitor sales, and export receipts.

---

## 📁 Project Structure

```
florist_pos/
├── index.php                # Main sales interface for staff
├── admin.php                # Admin dashboard for inventory and sales
├── checkout.php             # Handles checkout, transaction, and receipt
├── login.php                # Admin login logic
├── update_cart.php          # Updates item quantity in cart (AJAX)
├── update_stock.php         # Handles admin stock edits and image uploads
├── export_csv.php           # Exports sales data to CSV
│
├── assets/
│   ├── css/
│   │   └── style.css        # Main UI styles and print media queries
│   └── js/
│       └── script.js        # Cart logic, admin modal, receipt popup
│
├── includes/
│   ├── db.php               # Database connection
│   └── functions.php        # Helper functions for item/transaction handling
│
├── sql/
│   └── florist_pos.sql      # SQL schema and sample data
│
├── uploads/                 # Stores uploaded item images
└── receipts/                # Stores text-format receipts (e.g., receipt_1.txt)
```

---

## 🚀 Features

### 👥 Roles

- **Staff View**

  - View flower items with name, image, stock, and cart count
  - Add/remove quantity with `+` and `–` buttons
  - Cart dynamically updates total and availability
  - Checkout triggers:
    - Database update
    - Transaction recording
    - Popup receipt (styled like thermal paper)
    - Receipt options: **OK**, **Download .txt**, **Print**

- **Admin View**
  - Accessible via login modal
  - Edit item name, price, stock, and upload image
  - Activate/deactivate items (invisible to staff)
  - Add new item form with validation
  - Export all transactions as `.csv`

### 🧾 Receipt Format (Text + Print)

```
------------------------------
        Bloom Florist

       04.07.2025 12:25
------------------------------
           RECEIPT
------------------------------

 Daisies(7.00) x 3 = 21.00
 Orchids(15.00) x 3 = 45.00

     Total: 66.00

------------------------------
          Thank you
      Please come again
------------------------------
```

- Popup provides **3 buttons**:
  - ✅ OK — closes popup
  - 📄 Download — saves `.txt` file (e.g., `receipt_3.txt`)
  - 🖨️ Print — styled print with 62mm paper simulation

---

## 🛠️ Installation

1. **Download or clone the repository**
2. **Set up the database:**
   - Import `sql/florist_pos.sql` using phpMyAdmin or MySQL CLI
3. **Check file permissions:**
   - Ensure `uploads/` and `receipts/` folders are writable by the server
   - You may check the permission using `check_permission.php`
4. **Run the app:**
   - Place folder in XAMPP `htdocs` or equivalent
   - Open `index.php` to use the POS

---

## 🔑 Admin Login

- **Username**: `admin`
- **Password**: `admin`  
  _(SHA256 hashed in the `admins` table)_

---

## 🧰 Technologies Used

- HTML5 + CSS3
- JavaScript (vanilla)
- PHP (procedural)
- MySQL

---

## 🧪 Notes

- All checkout actions update stock only after confirmation
- Inactive items are preserved in DB for record keeping
- Print style auto-hides popup buttons during physical printing

---

## 📄 License

This project is for educational use and may be modified and reused freely.

---

## 🙏 Acknowledgements

This project was developed as part of the Web Technique and Applications course. It serves as a practical implementation of core concepts in web development, including front-end interface design, server-side scripting, database interaction, and basic system functionality. The system was designed to simulate a real-world florist point-of-sale environment for academic and learning purposes.
