# Anime Web Store

## Overview

Anime Web Store is a PHP-based e-commerce platform designed for anime fans to browse, purchase, and manage anime merchandise online.

The application provides product listings, shopping cart functionality, user authentication, checkout processing, and order management.

---

## Features

### User Features
- User Registration & Login
- Product Browsing
- Product Details Page
- Add Products to Cart
- Shopping Cart Management
- Secure Checkout
- Order Placement
- Order History Tracking
- Contact Page

### Admin Features
- Product Management
- Order Management
- User Management

---

## Project Structure

```
Anime_web_store/
│
├── index.php               # Homepage
├── products.php            # Product listing
├── product.php             # Product details
├── add_to_cart.php         # Add items to cart
├── cart.php                # Shopping cart
├── checkout.php            # Checkout page
├── place_order.php         # Order processing
├── order_success.php       # Order confirmation
├── orders.php              # User orders
├── signin.php              # User login
├── logout.php              # User logout
├── contact.php             # Contact page
└── _sidebar.php            # Sidebar component
```

---

## Technologies Used

- PHP
- HTML5
- CSS3
- JavaScript
- MySQL
- Apache/XAMPP

---

## Installation

### Clone Repository

```bash
git clone https://github.com/yourusername/Anime_web_store.git
```

### Move Project

Copy the project folder to:

```
xampp/htdocs/
```

### Start Services

Open XAMPP and start:

- Apache
- MySQL

### Create Database

1. Open phpMyAdmin
2. Create a new database

Example:

```sql
CREATE DATABASE anime_store;
```

3. Import the SQL file (if available)

### Configure Database

Update database credentials in your PHP configuration file:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "anime_store";
```

---

## Usage

Open your browser and visit:

```
http://localhost/Anime_web_store
```

Users can:

- Browse anime products
- Add items to cart
- Checkout orders
- Manage purchases

---

## Screenshots

Add screenshots of:

- Home Page
- Products Page
- Cart Page
- Checkout Page
- Orders Page

---

## Future Enhancements

- Online Payment Gateway Integration
- Product Search & Filters
- Wishlist Feature
- Product Reviews & Ratings
- Admin Dashboard
- Inventory Management
- Responsive Mobile Design

---

## Author

**Noneesh BS**

GitHub: https://github.com/NoneeshBS

---

## License

This project is licensed under the MIT License.
