<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# VeriTrack Authentication System

This Laravel project implements a multi-role authentication system for the VeriTrack application.

## Features

- Multi-role authentication (Administrator, Agency Staff, Public User)
- Role detection based on email domain (@admin.com, @agency.com, other)
- Registration with role-based validation
- Login with role-based redirection
- Password recovery and reset
- Profile editing with profile picture upload
- Session-based authentication

## Setup Instructions

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL
- XAMPP or similar local development environment

### Installation Steps

1. Clone the repository to your local environment
2. Navigate to the project directory
3. Install dependencies: `composer install`
4. Create a `.env` file by copying the example: `cp .env.example .env`
5. Configure your database in the `.env` file
6. Generate an application key: `php artisan key:generate`
7. Run the migrations to create the database tables: `php artisan app:setup-migrations`
8. Create a symbolic link for file storage: `php artisan app:create-storage-link`
9. Start the development server: `php artisan serve`

## Usage

### Default Users

The system seeds the following default users:

1. **Administrator**:
   - Email: admin@admin.com
   - Password: password123

2. **Agency Staff**:
   - Email: staff@agency.com
   - Password: password123

3. **Public User**:
   - Email: user@example.com
   - Password: password123

### Password Reset

1. Visit the password reset page at `/password/reset`
2. Enter your email address
3. Check your email for a password reset link
4. Click the link and enter your new password

### Profile Management

1. Click on your profile avatar in the header
2. Select "Edit Profile"
3. Update your personal information, profile picture, and password
4. Click "Save Changes"

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
