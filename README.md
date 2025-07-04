# 🚀 Laravel Project Setup Guide

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.1-blue)
![Node.js Version](https://img.shields.io/badge/Node.js-%3E%3D%2016.x-green)
![License](https://img.shields.io/badge/license-MIT-yellow.svg)
![Laravel](https://img.shields.io/badge/Laravel-Framework-red)

> 🎯 **This guide provides instructions to set up and run the Laravel project easily and efficiently.**

---

## 📦 Prerequisites

Make sure your system has the following installed:

- ✅ PHP >= **8.1**
- ✅ **Composer** (latest version)
- ✅ **Node.js** >= 16.x
- ✅ **NPM** or **Yarn**
- ✅ **MySQL/MariaDB** or another supported database
- ✅ **Git**

---

## 🔧 Installation Steps

### 1️⃣ Install PHP Dependencies

```bash
composer install
```

### 2️⃣ Install JavaScript Dependencies

```bash
npm install
# or
yarn install
```

### 3️⃣ Set Up Environment File

```bash
cp .env.example .env
```

🛠️ Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 5️⃣ Run Database Migrations

```bash
php artisan migrate
```

### 6️⃣ Seed the Database (Optional)

```bash
php artisan db:seed
```

### 7️⃣ Build Front-End Assets

Development:

```bash
npm run dev
```

Production:

```bash
npm run build
```

### 8️⃣ Clear Cache (Optional)

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 9️⃣ Start the Development Server

```bash
php artisan serve
```

📍 Access your app at: [http://localhost:8000](http://localhost:8000)

---

## 📝 Additional Notes

- ⚠️ Ensure your **database server is running** before running migrations.
- 🌐 For **production**, configure a web server like **Nginx** or **Apache**, and set correct file **permissions**.
- 🐛 If you encounter errors, check logs in:

```bash
storage/logs/laravel.log
```

---

## ❗ Troubleshooting

| Issue                    | Solution                                                                 |
|-------------------------|--------------------------------------------------------------------------|
| ⚙️ Composer issues       | Run `composer update` or verify your PHP version compatibility.           |
| 📦 NPM issues            | Run `npm cache clean --force` then retry `npm install`.                  |
| 🛢️ Database connection   | Double-check `.env` database settings (`DB_HOST`, `DB_PORT`, etc).        |

---

## 📬 Contact

Send your question about email: 19.nguyenvanduong@gmail.com - Duong Nguyen

> Happy Coding ❤️
