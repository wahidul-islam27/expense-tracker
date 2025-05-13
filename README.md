About Project

Its a simple php expense tracker project with responsive UI. Here User can add expense, generate report of monthly expenses.


Local Environment Setup

We have to download php and xampp to run the project.

1. Download PHP 
2. Download XAMPP
3. Set the environment variable of php (e.g: C:\xampp\php)

Composer setup

1. download the file: 
2. install it.
3. Run composer b


After completing the local environment setup, we can download the project. Move the project root folder into the C:\xampp\htdocs


Tun the project into your machine you have to do 
1. create a .env file into the root directory
2. copy the content of the .evn.example and paste in the .evn file
3. provide necessary DB configuration.


Debuging Project

1. Download the xdebug3 dll file
2. rename this as php_xdebug.dll
3. move this file into C:\xampp\php\ext
3. add the line into C:\xampp\php\php.ini file 
    zend_extension = xdebug
4. Install the vsCode php Debug extension.

PHP Unit
1. run composer require --dev phpunit/phpunit


