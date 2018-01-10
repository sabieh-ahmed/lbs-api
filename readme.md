# LBS API Environment Setup Guide


## Prerequisite

* PHP 7.1
* [Composer](https://getcomposer.org/doc/00-intro.md)
* Apache (optional)
* MySQL

## Code Setup
* Clone LBS from `https://github.com/sabieh-ahmed/lbs-api.git`
* Switch your branch from `master` to `develop`
* Rename `.env.sample` to `.env` and update DB configs
* Run `composer install`
* Create database `lbsdb` in mysql
* Run `php artisan migrate`
* Run `php artisan db:seed`
* Run if __seed is not working__ `composer dump-autoload` the Run again `php artisan db:seed`
* Run `php artisan serve`


## Code Style Guidelines
Code must comply with the code style guidelines. LBS API project follows the [__PSR-2__](http://www.php-fig.org/psr/psr-2/) coding standard and the [__PSR-4__](http://www.php-fig.org/psr/psr-4/) autoloading standard.

### Setup PHP Code Sniffer
```
sudo pear channel-update pear.php.net
pear install PHP_CodeSniffer
```
##### 

# Editor preferences
Set your editor to the following settings to avoid common code inconsistencies and dirty diffs:
* PHP Code Sniffer with PSR-2
* [PHP Mess Dectector](https://phpmd.org/)
* Set encoding to UTF-8.

Consider documenting and applying these preferences to LBS project's `.editorconfig` file.
