# سامانه وکلای ایران

![Laravel](https://img.shields.io/badge/Laravel-12.x-orange)
![Livewire](https://img.shields.io/badge/Livewire-3.x-4e56a6)
![Filament](https://img.shields.io/badge/Filament-4.x-6b46c1)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)

**پلتفرم هوشمند ارتباط موکلین با وکلای متخصص ایران**

---

## ✨ درباره پروژه

سامانه وکلای ایران یک پلتفرم مدرن و کامل برای **جستجو، معرفی و ارتباط مستقیم** با وکلای پایه یک دادگستری است. این سیستم امکان انتخاب مهارت تخصصی، مشاهده پروفایل حرفه‌ای وکلا و تماس هوشمند را فراهم می‌کند.

## 🚀 ویژگی‌های کلیدی

- **جستجوی مهارت‌محور** با مرتب‌سازی بر اساس محبوبیت (تعداد بازدید)
- **پروفایل کامل وکیل** با طراحی حرفه‌ای و ریسپانسیو
- **سیستم تماس هوشمند**:
  - تماس مستقیم روی موبایل (`tel:`)
  - نمایش و کپی شماره روی دسکتاپ
  - کسر هزینه از کیف پول وکیل
  - ثبت تماس و ارسال SMS
- **پنل مدیریت قدرتمند** با FilamentPHP
- **پشتیبانی از پرداخت** با درگاه شتاب (Shetabit Payment)
- **حالت تاریک (Dark Mode)**
- **امنیت بالا** و بررسی موجودی کیف پول قبل از تماس

## 🛠 تکنولوژی‌ها

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 + Bootstrap 5
- **Admin Panel**: Filament 4
- **Payment**: Shetabit Payment
- **Database**: MySQL
- **Styling**: Bootstrap + Custom CSS

---

## 📥 نصب و راه‌اندازی

### پیش‌نیازها
- PHP 8.2 یا بالاتر
- Composer
- Node.js + npm
- MySQL

### مراحل نصب

```bash
# 1. کلون کردن پروژه
git clone https://github.com/Amin-Safari/Lawyer.git
cd Lawyer

# 2. نصب وابستگی‌ها
composer install

# 3. تنظیم فایل محیط
cp .env.example .env

# 4. تولید کلید اپلیکیشن
php artisan key:generate

# 5. اجرای مهاجرت‌ها
php artisan migrate

# 6. لینک کردن فایل‌های آپلود
php artisan storage:link

# 7. بیلد فرانت‌اند
npm install && npm run build

# 8. اجرای پروژه
php artisan serve


```
## 📜 لایسنس  
MIT © 2025 Amin-Safari  

---

Developed with ❤️ by Amin Safari
