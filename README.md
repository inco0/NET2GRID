# DESCRIPTION
Assignment project of NET2GRID for platform engineer in PHP using symfony and doctrine.

## REQUIREMENTS
[COMPOSER](https://getcomposer.org/)

[PHP 8.0](https://www.php.net/)

## INSTALLATION
`$ composer install`

## RUNNING
`$ php bin/console app:start producer` to run the application as a producer

`$ php bin/console app:start consumer` to run the application as a consumer

# TESTING
`$ php vendor/bin/phpunit` to run unit tests

# CLASS STRUCTURE

![alt text](https://i.imgur.com/NrhXssg.png)

# DATABASE SCHEME

| id(int) | value(int) | timestamp(varchar) | queue_name(varchar) |
|---------|------------|--------------------|---------------------|

- **id :** An ascending unique id integer
- **value :** The value field of the API
- **timestamp :** The timestamp saved as a string since PHP only supports 32 bit integers and it overflows
- **queue_name :** The name of the queue the message was polled from, only one exists in this particular example