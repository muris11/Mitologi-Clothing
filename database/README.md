# Database Backup

## Files

### 1. `backup_full.sql` (Recommended)
- **Complete database dump** with all data
- **Size:** ~128 KB
- **Tables:** 42 tables
- **Use for:** Full restoration, migration to new server
- **Restore:** `mysql -u root -p proyek3 < backup_full.sql`

### 2. `schema_only.sql`
- **Database structure only** (no data)
- **Use for:** Fresh installation, reviewing table structure
- **Restore:** `mysql -u root -p proyek3 < schema_only.sql`

## Database Information

- **Database Name:** `proyek3`
- **Engine:** MySQL 8.0+
- **Charset:** utf8mb4_unicode_ci
- **Total Tables:** 42

## Main Tables

### Core E-Commerce
- `users` - Customer accounts
- `products` - Product catalog
- `categories` - Product categories
- `carts` & `cart_items` - Shopping cart
- `orders` & `order_items` - Orders
- `addresses` - Customer addresses

### Content Management
- `hero_slides` - Homepage slider
- `features` - Feature highlights
- `testimonials` - Customer reviews
- `portfolio` - Portfolio items
- `partners` - Client partners
- `materials` - Product materials
- `order_steps` - Ordering process steps
- `facilities` - Production facilities
- `team_members` - Team structure

### CMS Pages
- `pages` - Static pages (tentang-kami, layanan, kontak, etc.)
- `site_settings` - Site configuration

### Configuration & Logs
- `configurations` - App configuration
- `config_audit_logs` - Config change logs
- `rfid_scans` - RFID tracking
- `interactions` - ML interaction tracking

### System
- `migrations` - Laravel migrations
- `cache` & `cache_locks` - Application cache
- `jobs` & `failed_jobs` - Queue jobs
- `sessions` - User sessions
- `password_reset_tokens` - Password resets
- `personal_access_tokens` - API tokens

## Restore Instructions

### Full Restore (with data)
```bash
mysql -u root -p -h 127.0.0.1 -P 3306 proyek3 < database/backup_full.sql
```

### Schema Only (fresh start)
```bash
mysql -u root -p -h 127.0.0.1 -P 3306 proyek3 < database/schema_only.sql
# Then run seeders:
php artisan db:seed
```

### Create New Database
```bash
mysql -u root -p -e "CREATE DATABASE proyek3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p proyek3 < database/backup_full.sql
```

## Backup Schedule Recommendation

- **Daily:** Automated dump for development
- **Before deployments:** Always backup before major changes
- **Weekly:** Full backup archive

## Export Command Used

```bash
mysqldump -h 127.0.0.1 -P 3306 -u root \
  --databases proyek3 \
  --routines --triggers \
  --single-transaction --quick \
  --lock-tables=false \
  > database/backup_full.sql
```

## Last Updated

- **Date:** April 9, 2026
- **Commit:** Latest main branch
