PHP Expense Tracker

A simple PHP-based expense tracker with a responsive user interface.
Users can add expenses and generate monthly expense reports.
 

Local Environment Setup

Prerequisites
1. PHP
2. XAMPP

Setup Steps

1. Install PHP and XAMPP
    a. Download and install both PHP and XAMPP.
    b. Set the PHP environment variable (e.g., C:\xampp\php).

2. Install Composer
    a. Download and install Composer from https://getcomposer.org/download/.
    b. Verify installation by running: composer -v

3. Set up the project
    a. Clone or download the project repository.
    b. Move the project root folder into C:\xampp\htdocs.

5. Configure environment variables
    a.Create a .env file in the project root directory.
    b. Copy the content from .env.example and update the database configurations accordingly.


Debugging Setup (Xdebug 3)
1. Download the appropriate Xdebug 3 DLL file for your PHP version from https://xdebug.org/download.
2. Rename the downloaded file to php_xdebug.dll
3. Move the file into C:\xampp\php\ext
4. Edit your php.ini file (C:\xampp\php\php.ini) and add: zend_extension = php_xdebug.dll
5. Install the PHP Debug extension for VSCode.
6. Restart XAMPP services.

Running Unit Tests with PHPUnit

1. Install PHPUnit via Composer: composer require --dev phpunit/phpunit
2. Run tests: ./vendor/bin/phpunit
3. Coverage Report: vendor/bin/phpunit --coverage-html coverage-report/
