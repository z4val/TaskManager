# Task Manager API
![Badge](https://img.shields.io/badge/STATUS-IN%20DEVELOPMENT-green)

A RESTful API built with Laravel for managing task lists and tasks, featuring JWT authentication and CRUD operations.

## Features

- 🔐 **JWT Authentication**: Secure user registration, login, and token management.
- 📋 **Task List Management**: Create, read, update, and delete task lists with customizable colors.
- ✅ **Task Operations**: Mark tasks as complete/incomplete, and manage task descriptions.
- 🔗 **Relational Data**: Each user owns multiple task lists, each containing multiple tasks.

## Prerequisites

- PHP ≥ 8.1
- Composer
- MySQL/PostgreSQL/SQLite
- JWT Secret Key (generated during setup)

## Installation

1. **Clone the repository**:
```bash
git clone https://github.com/yourusername/task-manager-api.git
cd task-manager-api
```
2. **Install dependencies**:
```bash
composer install
```
3. **Configure environment:**: 
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```
4. **Set up database:**
```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_manager
    DB_USERNAME=root
    DB_PASSWORD=
```
5. **Run migrations:**
```bash
php artisan migrate
```
6. **Start the server:**
```bash
php artisan serve
```

API will be available at `http://localhost:8000`.