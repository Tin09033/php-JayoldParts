# JayOldParts - Second-Hand Motor Parts E-Commerce

A full-stack PHP e-commerce website for selling second-hand motor vehicle parts with admin panel.

## Features

### Frontend
- Modern, responsive UI with Bootstrap 5
- Product listing with filters and search
- Product detail pages with multiple images
- Shopping cart and checkout system
- User authentication (register/login)
- User dashboard with order history
- Wishlist functionality
- Product reviews and ratings

### Admin Panel
- Dashboard with sales overview
- Product management (add/edit/delete)
- Category management
- Order management with status updates
- Payment verification (GCash)
- User management
- Banner management

### Payment
- GCash payment integration
- Manual payment proof upload
- Admin verification system

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

## Installation

### 1. Database Setup

1. Open phpMyAdmin or MySQL command line
2. Create a new database named `jayoldparts`
3. Import the `database.sql` file:
   ```
   mysql -u root -p jayoldparts < database.sql
   ```

### 2. Configuration

Edit `includes/config.php` to update database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'jayoldparts');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
```

### 3. Upload Files

Upload all files to your web server (public_html or htdocs).

### 4. Default Admin Credentials

- Email: admin@jayoldparts.com
- Password: password

### 5. Create Test User

Visit `/pages/register.php` to create a test user account.

## Demo Data

The database includes:
- 6 default categories
- Sample banners
- Default settings

Add products through the admin panel at `/admin/`.

## Project Structure

```
/jayoldparts
├── admin/              # Admin panel
│   ├── products/       # Product management
│   ├── orders/        # Order management
│   ├── categories/    # Category management
│   ├── users/         # User management
│   ├── banners/       # Banner management
│   └── login.php      # Admin login
├── assets/
│   ├── css/          # Stylesheets
│   └── js/           # JavaScript files
├── components/        # Reusable components
├── includes/          # Core PHP files
├── pages/             # Frontend pages
├── uploads/          # Uploaded files
├── index.php         # Main entry point
├── database.sql      # Database schema
└── SPEC.md           # Detailed specification
```

## Usage

### Adding Products (Admin)

1. Login to admin panel at `/admin/`
2. Go to Products > Add Product
3. Fill in product details
4. Upload product images
5. Save product

### Placing Orders (User)

1. Register/Login
2. Browse products
3. Add to cart
4. Go to checkout
5. Fill shipping details
6. Upload GCash payment proof (for GCash payment)
7. Place order

### Verifying Payments (Admin)

1. Login to admin panel
2. Go to Orders
3. View order details
4. Check payment proof image
5. Click verify/reject button

## Security

- Passwords hashed with bcrypt
- Prepared statements for all SQL queries
- CSRF protection on forms
- Input sanitization
- Session management

## License

This project is for educational purposes.

## Author

Created for JayOldParts motor parts business.
