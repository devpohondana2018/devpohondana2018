## About Pohon Dana

P2P lending platform built for Mayapada group on top of Laravel v5.5 framework. [Visit website](https://pohondana.id)

## Development Setup

Below is the suggested steps to make sure the app runs accordingly during development:

-- Mandatory

- Clone **master repository**
- Create **empty database**
- Configure **.env** file parameters
- Run **composer update**
- Run **composer dump-autoload**
- Run migration: **php artisan migrate**
- Run admin install: **php artisan admin:install**
- Seed sample database: **php artisan db:seed**
- Run **php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"**

-- Optional

- php artisan admin:import config
- php artisan vendor:publish --tag=api-tester
- php artisan admin:import api-tester
- php artisan admin:import helpers
- php artisan admin:import log-viewer
- Follow installations steps for [Reporter](https://github.com/laravel-admin-extensions/reporter)

## Demo User Access /login

Inactive user

- Email: user@user.com
- Password: password

Borrower user

- Email: borrower1@user.com - borrower5@user.com
- Password: password

Lender user

- Email: lender1@user.com - lender5@user.com
- Password: password

## Admin Access /admin

Below is the default admin access, proceed with caution:

- Username: admin
- Password: admin

## Root Server Access

Below is the server access, proceed with caution:

- IP address: 139.255.57.54
- Root: sysadmin
- Pass: Asclar2018!

## MySQL Database Server Access

Below is the database server access, proceed with caution:

- Username: root
- Pass: qazplm123

## .env GMail SMTP Configuration


## .env Mailtrap SMTP Configuration

Below is the mailtrap configuration for development purpose only:

- MAIL_DRIVER=smtp
- MAIL_HOST=smtp.mailtrap.io
- MAIL_PORT=2525
- MAIL_USERNAME=8341069ff52509
- MAIL_PASSWORD=a987beb6f7b310
- MAIL_ENCRYPTION=null
- MAIL\_FROM\_ADDRESS=info@pohondana.id
- MAIL\_FROM\_NAME="Pohon Dana"

## Midtrans PG Integration

## Midtrans IRIS Integration

## API Integration

## Library Dependencies

- [Laravel v5.5](https://laravel.com/docs/5.5)
- [Laravel Admin](https://github.com/z-song/laravel-admin)
- [Laravel Admin Extensions](https://github.com/laravel-admin-extensions)
- [Laravel Permission](https://github.com/spatie/laravel-permission)
- [Laravel Backup](https://github.com/spatie/laravel-backup)
- [Laravel Lang](https://github.com/caouecs/Laravel-lang)