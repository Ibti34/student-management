# Student Management System

This is a **Student Management System** built with **Laravel, Vue, and Inertia.js**.  
It allows admins to manage student records and track attendance.


## Features

- User authentication (login, register) using **Laravel Breeze**
- Add, update, delete, and view students
- Record and view student attendance
- Admin-only access to certain pages
- Dashboard for overview


## Technologies Used

Backend: PHP, Laravel, Laravel Breeze, Inertia.js  
Frontend: Vue.js, Blade templates, Tailwind CSS  
Database: SQLite (default) 


## Installation

1. Clone the repository:
   
git clone https://github.com/Ibti34/student-management.git

2.composer install
  npm install

3.cp .env.example .env
php artisan key:generate

4.php artisan migrate

5.npm run dev

6.php artisan serve
