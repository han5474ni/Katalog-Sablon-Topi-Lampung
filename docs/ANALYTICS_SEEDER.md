# Analytics Dashboard Seeder

Seeder ini membuat data orders yang sudah completed dan paid untuk testing analytics dashboard.

## Fitur

- ✅ Membuat 5 test customers dengan email terverifikasi
- ✅ Membuat 5 produk dengan variants (colors dan sizes)
- ✅ Membuat 20 orders dengan status `completed` dan `payment_status` = `paid`
- ✅ Total 29 produk terjual
- ✅ Orders tersebar di 30 hari terakhir untuk testing trend data
- ✅ Data realistis dengan variation harga dan kuantitas

## Cara Menggunakan

### 1. Jalankan Seeder Saja (Jika Database Sudah Ada)

```bash
php artisan seed:analytics
```

Output:
```
✓ Analytics data seeded successfully!
✓ Visit http://127.0.0.1:8000/admin/analytic to see the data
✓ Expected metrics:
  - Total orders: 20
  - Total products sold: 29
  - Revenue: ~2,200,000 - 3,500,000 Rp (varies with random prices)
```

### 2. Fresh Database dengan Seeder

```bash
php artisan seed:analytics --fresh
```

Ini akan:
- Drop semua tables
- Jalankan migrations
- Seed admin + analytics data

### 3. Manual Seed dengan Database Seeder

```bash
php artisan db:seed
```

Ini akan menjalankan `DatabaseSeeder` yang includes `CompletedOrdersAnalyticsSeeder`

## Test Customers

Berikut adalah customers yang dibuat untuk testing:

```
Email: analytics_customer_1@test.com
Email: analytics_customer_2@test.com
Email: analytics_customer_3@test.com
Email: analytics_customer_4@test.com
Email: analytics_customer_5@test.com

Password: password123
```

## Expected Analytics Data

Setelah seeding, analytics dashboard harus menampilkan:

| Metric | Value |
|--------|-------|
| Total Orders (Completed) | 20 |
| Total Products Sold | 29 |
| Total Revenue | ~2,200,000 - 3,500,000 Rp |
| Average Order Value | ~110,000 - 175,000 Rp |
| New Customers (Last Period) | 5 |

## Data Distribution

Orders dibuat dengan pattern:
- **User 1**: 3 orders (repeat buyer)
- **User 2**: 3 orders (regular buyer)
- **User 3**: 2 orders (bulk buyer, high quantity)
- **User 4**: 2 orders (frequent small orders)
- **User 5**: 2 orders (recent buyer)

Total: 20 orders across 30 days

## Test Products

```
1. Kaos Premium Basic - 85,000 Rp
2. Kaos Custom Design - 120,000 Rp
3. Kaos Couple Pack - 150,000 Rp
4. Jersey Sport - 95,000 Rp
5. Polo Shirt Premium - 110,000 Rp
```

Setiap product memiliki 3 variants dengan colors berbeda (Red, Blue, Black)

## Verifikasi Data

Login ke `/admin` dan cek:

1. **Dashboard Analytics** (`/admin/analytic`)
   - Total Revenue
   - Total Orders
   - Average Order Value
   - Sales Trend Chart
   - Order Status Distribution
   - Customer Analytics

2. **Order Management** (`/admin/orders`)
   - Filter by status: completed
   - Filter by payment_status: paid
   - Verify order details

3. **Customer Management** (`/admin/users`)
   - View test customers
   - Check order history

## Catatan

- Seeder cek jika data sudah ada, jadi aman dijalankan berkali-kali
- Untuk reseed, gunakan `php artisan migrate:refresh --seed`
- Timestamps orders tersebar di 30 hari terakhir untuk realistic data
- Shipping cost dan discount random untuk variation data

## Troubleshooting

**Q: Seeder tidak membuat orders?**
A: Pastikan database sudah fresh. Jalankan: `php artisan migrate:fresh --seed`

**Q: Analytics tidak menampilkan data?**
A: 
1. Clear cache: `php artisan cache:clear`
2. Clear views: `php artisan view:clear`
3. Refresh browser

**Q: Mau reseed ulang?**
A: Jalankan: `php artisan migrate:refresh --seed`
