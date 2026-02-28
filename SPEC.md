# JayOldParts - Second-Hand Motor Parts E-Commerce

## Project Overview
- **Project Name**: JayOldParts
- **Type**: Full-stack PHP E-commerce Website
- **Core Functionality**: Online marketplace for buying and selling second-hand motor vehicle parts with admin management
- **Target Users**: Motor vehicle owners, mechanics, auto repair shops, and part resellers

---

## Technical Stack
- **Backend**: Core PHP with MVC Architecture
- **Frontend**: Bootstrap 5 + Custom CSS + Vanilla JavaScript
- **Database**: MySQL
- **Authentication**: Session-based with password hashing (bcrypt)
- **Image Handling**: File upload with validation

---

## UI/UX Specification

### Color Palette
- **Primary**: `#FF6B00` (Automotive Orange)
- **Secondary**: `#1A1A2E` (Dark Navy)
- **Dark**: `#16213E` (Deep Blue)
- **Light**: `#F8F9FA` (Off White)
- **Accent**: `#E94560` (Alert Red)
- **Success**: `#00C851` (Green)
- **Warning**: `#FFBB33` (Amber)
- **Text Dark**: `#2D3436`
- **Text Light**: `#FFFFFF`
- **Card Dark**: `#1E1E2F`
- **Border**: `#2D2D44`

### Typography
- **Primary Font**: 'Poppins', sans-serif (Headings)
- **Secondary Font**: 'Open Sans', sans-serif (Body)
- **Heading Sizes**: H1: 2.5rem, H2: 2rem, H3: 1.5rem, H4: 1.25rem
- **Body Size**: 1rem (16px)
- **Small Text**: 0.875rem

### Spacing System
- **Base Unit**: 8px
- **XS**: 4px, **SM**: 8px, **MD**: 16px, **LG**: 24px, **XL**: 32px, **XXL**: 48px

### Responsive Breakpoints
- **Mobile**: < 576px
- **Tablet**: 576px - 991px
- **Desktop**: >= 992px

### Layout Structure

#### Header (All Pages)
- Sticky navbar with dark background (#1A1A2E)
- Logo (left): "JayOldParts" with gear icon
- Navigation (center): Home, Products, Categories, About, Contact
- Right side: Search icon, Wishlist, Cart (with badge), User dropdown
- Mobile: Hamburger menu with slide-out drawer

#### Homepage
1. **Hero Section**
   - Full-width slider with 3 promotional banners
   - Bold headline with CTA buttons
   - Animated search bar

2. **Categories Section**
   - 6 category cards in grid (2x3 mobile, 3x2 tablet, 6x1 desktop)
   - Icon + Name + Item count
   - Hover: scale + orange border glow

3. **Featured Products**
   - Section title with "View All" link
   - Product grid: 4 columns desktop, 2 mobile
   - Product card: Image, Name, Condition badge, Price, Add to Cart

4. **Promotions Section**
   - Banner with discount offer
   - Countdown timer (JavaScript)
   - Limited offers carousel

5. **Why Choose Us**
   - 4 feature boxes (Fast Shipping, Quality Parts, Secure Payment, Support)

6. **Footer**
   - 4 columns: About, Quick Links, Categories, Contact
   - Social media icons
   - Newsletter signup
   - Copyright

#### Product Listing Page
- Breadcrumb navigation
- Sidebar filters (desktop) / Filter button (mobile)
- Search bar at top
- Sort dropdown (Price Low-High, High-Low, Newest)
- Product grid with pagination
- Filter options: Category, Price Range, Condition, Brand

#### Product Detail Page
- Image gallery with thumbnails
- Product info: Name, SKU, Condition badge, Price
- Description tabs (Description, Specifications, Compatibility)
- Stock status indicator
- Quantity selector
- Add to Cart / Buy Now buttons
- Seller info card
- Related products section
- Reviews section with rating

#### Shopping Cart
- Table view with product images, names, quantities, prices
- Update quantity / Remove buttons
- Order summary sidebar
- Apply coupon code
- Proceed to Checkout button

#### Checkout Page
- Multi-step form (Shipping > Payment > Confirm)
- Address form with validation
- Payment method selection (GCash upload)
- Order review
- Place Order button

#### User Dashboard
- Sidebar navigation
- Overview cards (Orders, Wishlist, Reviews)
- Order history with status
- Profile settings
- Address book
- Wishlist
- My reviews

#### Admin Panel
- Sidebar navigation (dark theme)
- Top stats cards with icons
- Charts for sales/orders
- Data tables with actions
- Forms for CRUD operations

---

## Database Schema

### users
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(100) | NOT NULL |
| email | VARCHAR(100) | UNIQUE, NOT NULL |
| phone | VARCHAR(20) | |
| password | VARCHAR(255) | NOT NULL |
| avatar | VARCHAR(255) | |
| address | TEXT | |
| role | ENUM('user', 'admin') | DEFAULT 'user' |
| status | ENUM('active', 'inactive') | DEFAULT 'active' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

### admins
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| username | VARCHAR(50) | UNIQUE, NOT NULL |
| email | VARCHAR(100) | UNIQUE, NOT NULL |
| password | VARCHAR(255) | NOT NULL |
| name | VARCHAR(100) | NOT NULL |
| avatar | VARCHAR(255) | |
| role | ENUM('super_admin', 'admin') | DEFAULT 'admin' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

### categories
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(100) | NOT NULL |
| slug | VARCHAR(100) | UNIQUE, NOT NULL |
| description | TEXT | |
| image | VARCHAR(255) | |
| parent_id | INT | NULL, FOREIGN KEY |
| status | ENUM('active', 'inactive') | DEFAULT 'active' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

### products
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(200) | NOT NULL |
| slug | VARCHAR(200) | UNIQUE, NOT NULL |
| sku | VARCHAR(50) | UNIQUE |
| category_id | INT | FOREIGN KEY |
| brand | VARCHAR(100) | |
| description | TEXT | |
| price | DECIMAL(10,2) | NOT NULL |
| original_price | DECIMAL(10,2) | |
| condition | ENUM('used', 'like_new', 'refurbished') | DEFAULT 'used' |
| stock | INT | DEFAULT 0 |
| weight | DECIMAL(10,2) | |
| compatibility | TEXT | |
| status | ENUM('active', 'inactive', 'out_of_stock') | DEFAULT 'active' |
| is_featured | TINYINT(1) | DEFAULT 0 |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

### product_images
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| product_id | INT | FOREIGN KEY |
| image | VARCHAR(255) | NOT NULL |
| is_primary | TINYINT(1) | DEFAULT 0 |
| sort_order | INT | DEFAULT 0 |

### orders
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| user_id | INT | FOREIGN KEY |
| order_number | VARCHAR(50) | UNIQUE, NOT NULL |
| subtotal | DECIMAL(10,2) | NOT NULL |
| shipping_fee | DECIMAL(10,2) | DEFAULT 0 |
| total | DECIMAL(10,2) | NOT NULL |
| shipping_name | VARCHAR(100) | |
| shipping_phone | VARCHAR(20) | |
| shipping_address | TEXT | |
| shipping_city | VARCHAR(100) | |
| shipping_zip | VARCHAR(20) | |
| payment_method | ENUM('gcash', 'cod', 'bank_transfer') | |
| payment_status | ENUM('pending', 'paid', 'verified', 'rejected') | DEFAULT 'pending' |
| payment_proof | VARCHAR(255) | |
| order_status | ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') | DEFAULT 'pending' |
| notes | TEXT | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

### order_items
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| order_id | INT | FOREIGN KEY |
| product_id | INT | FOREIGN KEY |
| product_name | VARCHAR(200) | |
| product_image | VARCHAR(255) | |
| price | DECIMAL(10,2) | NOT NULL |
| quantity | INT | NOT NULL |
| subtotal | DECIMAL(10,2) | NOT NULL |

### payments
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| order_id | INT | FOREIGN KEY |
| user_id | INT | FOREIGN KEY |
| amount | DECIMAL(10,2) | NOT NULL |
| method | VARCHAR(50) | |
| reference_number | VARCHAR(100) | |
| proof_image | VARCHAR(255) | |
| status | ENUM('pending', 'verified', 'rejected') | DEFAULT 'pending' |
| notes | TEXT | |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| verified_by | INT | FOREIGN KEY |
| verified_at | TIMESTAMP | |

### wishlists
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| user_id | INT | FOREIGN KEY |
| product_id | INT | FOREIGN KEY |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

### reviews
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| product_id | INT | FOREIGN KEY |
| user_id | INT | FOREIGN KEY |
| rating | TINYINT(1-5) | NOT NULL |
| comment | TEXT | |
| status | ENUM('pending', 'approved', 'rejected') | DEFAULT 'pending' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

### banners
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| title | VARCHAR(200) | |
| subtitle | VARCHAR(200) | |
| image | VARCHAR(255) | NOT NULL |
| link | VARCHAR(255) | |
| position | ENUM('hero', 'promo', 'banner') | |
| status | ENUM('active', 'inactive') | DEFAULT 'active' |
| sort_order | INT | DEFAULT 0 |

### settings
| Column | Type | Constraints |
|--------|------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| key | VARCHAR(100) | UNIQUE, NOT NULL |
| value | TEXT | |

---

## Functionality Specification

### Authentication
- User registration with email verification (optional)
- Login with email/password
- Password reset via email
- Session-based authentication
- Remember me functionality
- CSRF protection on all forms

### Product Management (Admin)
- CRUD operations for products
- Multiple image upload (max 5)
- Set product condition
- Featured product toggle
- Stock management
- Category assignment

### Order Management
- Order creation from cart
- Order status workflow: Pending → Processing → Shipped → Delivered
- Payment proof upload (GCash)
- Admin payment verification
- Order cancellation (pending orders only)

### Search & Filter
- Full-text search on products
- Filter by category, price range, condition
- Sort by price, date, popularity
- SEO-friendly URLs (/product/product-slug)

### Cart System
- Add/remove products
- Update quantities
- Persistent cart (database)
- Cart total calculation

### Payment Flow
1. User selects GCash payment
2. User uploads payment screenshot
3. Admin verifies payment
4. Order status updates to processing

---

## Security Measures
- Prepared statements for all queries
- Input sanitization
- XSS protection
- CSRF tokens on forms
- Password hashing (bcrypt)
- Session timeout
- File upload validation (type, size)
- Access control (user vs admin routes)

---

## File Structure
```
/jayoldparts
├── /admin
│   ├── /assets
│   │   ├── /css
│   │   ├── /js
│   │   └── /images
│   ├── /includes
│   ├── index.php
│   ├── login.php
│   ├── dashboard.php
│   ├── products/
│   ├── orders/
│   ├── users/
│   ├── categories/
│   └── banners/
├── /assets
│   ├── /css
│   │   ├── style.css
│   │   └── custom.css
│   ├── /js
│   │   └── main.js
│   └── /images
├── /includes
│   ├── config.php
│   ├── database.php
│   ├── functions.php
│   ├── auth.php
│   └── helpers.php
├── /uploads
│   ├── /products
│   ├── /payments
│   └── /users
├── /pages
│   ├── home.php
│   ├── products.php
│   ├── product-detail.php
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php
│   ├── wishlist.php
│   ├── profile.php
│   └── auth/
├── index.php
└── .htaccess
```

---

## Acceptance Criteria

### Frontend
- [ ] Homepage loads with all sections
- [ ] Categories display correctly
- [ ] Products display in grid with pagination
- [ ] Product detail shows all information
- [ ] Cart functionality works (add, update, remove)
- [ ] Checkout process completes
- [ ] User can view order history
- [ ] Responsive design works on all devices
- [ ] Dark mode toggle functions
- [ ] Search returns relevant results

### Backend
- [ ] User registration and login work
- [ ] Admin login works
- [ ] Products can be added/edited/deleted
- [ ] Orders can be managed
- [ ] Payment verification works
- [ ] All forms have validation
- [ ] Database operations use prepared statements

### Security
- [ ] Passwords are hashed
- [ ] Sessions are secure
- [ ] Input is sanitized
- [ ] CSRF protection is implemented
- [ ] File uploads are validated
