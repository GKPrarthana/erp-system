# ERP System - Csquare Technologies Assignment

## Project Overview
Comprehensive ERP system built with PHP, MySQL, and Bootstrap for customer, inventory, and invoice management.

## Features
- **Dashboard**: Real-time metrics and analytics
- **Customer Management**: CRUD operations with search
- **Inventory Management**: Complete item tracking
- **Invoice System**: Invoice generation and management
- **Reports**: Advanced analytics and charts

## Technology Stack
- Backend: PHP 7.4+
- Database: MySQL 5.7+
- Frontend: Bootstrap 5, HTML5, CSS3, JavaScript
- Server: Apache (XAMPP)

## Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps
1. **Start XAMPP** - Ensure Apache and MySQL are running
2. **Create Database** - Open phpMyAdmin and create database `erp_db`
3. **Import Database** - Import `erp_system_database.sql`
4. **Place Project** - Copy project to `C:\xampp\htdocs\erp-system\`
5. **Access System** - Visit `http://localhost/erp-system/`

## Database Export
Run `export_database.bat` to create `erp_system_database.sql`

## File Structure
```
erp-system/
├── config/db.php          # Database configuration
├── customer/              # Customer management
├── item/                  # Inventory management
├── reports/               # Analytics and reports
├── partials/              # Header/footer components
├── assets/                # CSS/JS files
└── index.php              # Main dashboard
```

## Usage
- **Dashboard**: Overview and quick access
- **Customers**: Add, edit, delete, search customers
- **Items**: Manage inventory items
- **Reports**: View analytics and charts


---
