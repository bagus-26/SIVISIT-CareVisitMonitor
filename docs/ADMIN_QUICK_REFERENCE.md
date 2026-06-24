# 🔑 Quick Reference - Admin Login Credentials

## Default Admin Credentials

| Field | Value |
|-------|-------|
| **Email** | `admin@sivisit.com` |
| **Password** | `Admin123456` |
| **Role** | Administrator |
| **NIP** | 000000 |

## Default Staff Credentials (Demo)

| Field | Value |
|-------|-------|
| **Email** | `petugas@sivisit.com` |
| **Password** | `Petugas123456` |
| **Role** | Petugas |
| **NIP** | 000001 |

## Quick Setup

```powershell
# 1. Run migrations
php artisan migrate

# 2. Run seeders to create admin credentials
php artisan db:seed

# 3. Clear cache
php artisan cache:clear

# 4. Open browser and go to login page
# http://localhost/sivisit_CareVisitMonitor/login
```

## Main Menu in Dashboard

1. 📊 **Dashboard** - Overview & statistics
2. 👥 **Pasien** - Patient management
3. 👤 **Petugas** - Staff/Officer management
4. 🏥 **Kunjungan** - Visit/Monitoring records
5. 📈 **Laporan** - Reports & analytics
6. ⚙️ **Pengaturan** - Settings & account management

## Admin Actions

- ✅ View all data
- ✅ Create new records
- ✅ Edit existing records
- ✅ Delete records
- ✅ View reports and analytics
- ✅ Manage staff members
- ✅ Change password and profile

---

**Version:** 1.0.0 | **Status:** ✅ Ready to Use
