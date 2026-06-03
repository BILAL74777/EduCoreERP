# SBA Management System

> Petrol Pump & General Store ERP System built with Laravel, Vue 3, Inertia.js, Sanctum, and MySQL.

---

## 📖 Overview

SBA Management System is a complete ERP solution designed to manage two business domains within a single platform:

* 🛢️ Petrol Pump Management
* 🏪 General Store Management

The system provides inventory control, supplier management, customer credit tracking, profit calculation, partner profit distribution, expense management, reporting, and ledger accounting.

---

# 🚀 Technology Stack

## Backend

* Laravel 13
* PHP 8.3+
* Laravel Sanctum
* MySQL

## Frontend

* Vue 3
* Inertia.js
* Axios
* Tailwind CSS
* Vite

## Reporting

* Laravel DomPDF
* Laravel Excel

---

# 🏗️ System Architecture

```text
Frontend (Vue 3 + Inertia)
            │
            ▼
      Laravel Backend
            │
            ▼
          MySQL
```

---

# 📦 Core Modules

## 🛢️ Petrol Pump Module

### Supplier Management

Manage fuel suppliers and supplier ledgers.

Features:

* Fuel purchase records
* Payment tracking
* Outstanding balance tracking
* Supplier ledger reports

### Partners Management

Manage business partners and profit sharing.

Features:

* Partner registration
* Share percentage management
* Monthly profit distribution
* Distribution reports

### Permanent Customers

Manage customers purchasing fuel on credit.

Features:

* Customer ledger
* Credit tracking
* Monthly settlement
* Payment history

### Fuel Transactions

Manage daily fuel sales.

Features:

* Cash sales
* Credit sales
* Dynamic fuel pricing
* Profit tracking

### Fuel Rate Management

Manage changing fuel prices.

Features:

* Historical pricing
* Purchase rate tracking
* Selling rate tracking
* Automatic profit calculations

### Expenses Management

Track operational expenses.

Features:

* Salaries
* Utility bills
* Maintenance expenses
* Miscellaneous expenses

### Profit Engine

Automatically calculates:

```text
Revenue
- Fuel Cost
- Expenses
= Net Profit
```

Partner profit:

```text
Partner Share = Net Profit × Share %
```

---

## 🏪 General Store Module

### Supplier Management

Manage suppliers and installment payments.

Features:

* Purchase records
* Supplier ledger
* Due balance tracking

### Inventory Management

Complete stock control system.

Features:

* Categories
* Products
* Stock history
* Purchase tracking
* Price history
* Inventory valuation

### Sales Management

Features:

* Cash sales
* Credit sales
* Invoice generation
* Profit tracking

### Customer Credit System

Features:

* Credit customers
* Customer ledger
* Monthly billing
* Payment management

### Inventory Profit System

Features:

* Item-wise profit
* Monthly profit reports
* Inventory performance analysis

---

# 📒 Ledger System

The platform maintains separate ledgers for:

* Suppliers
* Customers
* Partners

Every financial transaction automatically updates the relevant ledger.

---

# 📊 Reporting System

Supported reports:

### Petrol Pump Reports

* Fuel Purchase Report
* Supplier Ledger
* Customer Ledger
* Expense Report
* Monthly Profit Report
* Partner Distribution Report

### Store Reports

* Inventory Report
* Stock Movement Report
* Supplier Ledger
* Customer Ledger
* Sales Report
* Profit Analysis

### Export Formats

* PDF
* Excel (XLSX)

---

# 📈 Business Rules

## Dynamic Pricing

Fuel prices may change weekly.

Store item prices may vary based on purchase history.

---

## Credit Management

Supported for:

* Petrol Customers
* Store Customers
* Suppliers

Monthly settlements are supported.

---

## Profit Distribution

Partner distributions are calculated only after:

* Revenue calculation
* Cost deduction
* Expense deduction

---

# 🗄️ Database Modules

## Petrol Pump

* Suppliers
* Fuel Purchases
* Fuel Stock
* Fuel Transactions
* Customers
* Customer Payments
* Expenses
* Partners
* Profit Distribution

## General Store

* Suppliers
* Products
* Categories
* Inventory
* Purchases
* Sales
* Customers
* Customer Payments

---

# 📂 Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│
├── Services/
│   ├── ProfitService.php
│   ├── LedgerService.php
│   ├── PricingService.php
│
├── Models/

resources/
├── js/
│   ├── Pages/
│   ├── Components/
│   ├── Layouts/
│
├── css/

routes/
├── web.php
├── api.php
```

---

# 🛠️ Installation

Clone repository:

```bash
git clone https://github.com/yourusername/asb-business.git
```

Install PHP dependencies:

```bash
composer install
```

Install Node dependencies:

```bash
npm install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure database credentials in `.env`

Run migrations:

```bash
php artisan migrate
```

Start development server:

```bash
php artisan serve
```

Start Vite:

```bash
npm run dev
```

---

# 🔐 Authentication

Authentication is powered by:

* Laravel Breeze
* Laravel Sanctum
* Inertia.js
* Vue 3

Features:

* Login
* Registration
* Password Reset
* Email Verification
* Profile Management

---

# 🎯 Future Enhancements

* Multi Branch Support
* POS System
* Barcode Scanner Integration
* Fuel Nozzle Integration
* SMS Notifications
* WhatsApp Notifications
* Mobile Application
* Advanced Analytics Dashboard
* Audit Logs
* Role & Permission Management

---

# 👨‍💻 Developed By

**Bilal Hussain Full Stack Dev.**

ERP System for Petrol Pump & General Store Operations.

---

## License

This project is proprietary software and intended for business management purposes.
