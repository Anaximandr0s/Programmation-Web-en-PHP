# Laravel Project Setup Guide  

This guide will help you set up and run the Laravel project. Follow the steps below to ensure the project is properly configured and running.  

## Prerequisites  
Make sure the following are installed on your machine:  
- PHP (>= 8.0)
- Composer  
- MySQL  
- Node.js and npm  

---

## Step 1: Clone the Repository  
Clone the Laravel project repository to your local machine:  
```bash
git clone <repository-url>
```

```bash
cd <project-folder>
```

## Step 2: Set Up the Database

```bash
CREATE DATABASE laravel_project;
```

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_project
DB_USERNAME=root
DB_PASSWORD=
```

## Step 3: Install Dependencies

```bash
composer install
```

```bash
npm install
```
## Step 4: Run Database Migrations

```bash
php artisan migrate
```

## Step 5: Start the Development Server

```bash
php artisan serve
```

