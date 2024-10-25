## Requirements

    PHP 8.*

    MySQL DB

## Dependency Setup

    composer install

## Update Local DB Credential

    DB_CONNECTION=mysql

    DB_HOST=127.0.0.1

    DB_PORT=3306

    DB_DATABASE=aldmic

    DB_USERNAME=root

    DB_PASSWORD=

    # DB_SOCKET=/tmp/mysql.sock

## DB & Data Preparation

    php artisan migrate

    php artisan db:seed

    php artisan optimize:clear

## Run Application

    php artisan serve --host=localhost --port=7777

## Login Page

    URL : http://localhost:7777/

    Username: aldmic

    Password: 123abc123

## Demo

    https://www.loom.com/share/034c29c2041b459b90f6f88b9fb71862
