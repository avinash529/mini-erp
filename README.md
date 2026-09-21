# Mini ERP — Inventory & Purchase Order Lifecycle Management System

A Laravel-based Mini ERP application for managing suppliers, product inventory, purchase orders, stock receiving, and supplier expenditure reporting.

The application provides both a Blade-based web interface and REST APIs secured using Laravel Sanctum.

---

## Features

- Supplier management
- Product catalog and inventory management
- Purchase Order creation
- Purchase Order lifecycle management
- Automatic subtotal and total calculation
- Stock updates when Purchase Orders are received
- Low-stock monitoring
- Dashboard with inventory and expenditure statistics
- Supplier expenditure report
- Laravel Sanctum API authentication
- Database transaction safety
- Row locking for stock updates
- Eager loading to prevent N+1 queries
- Database indexing
- Automated PHPUnit tests

---

# Purchase Order Lifecycle

Purchase Orders follow the lifecycle below:

```text
DRAFT
  |
  v
APPROVED
  |
  v
RECEIVED