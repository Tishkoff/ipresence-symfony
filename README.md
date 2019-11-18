# iPresence Test Task

This implementation is based on Symfony 4.

## Installation

### Prerequisites:

* PHP >= 7.2
* composer installed globally

## Steps:

1. Unzip into some directory
2. `cd ipresence-symfony`
3. `composer install`
4. Run built-in php development server with command `php -S 127.0.0.1:8123 -t public`

That's it, you can go to the api endpoint: http://localhost:8123/shout/steve-jobs?limit=2

## Projects files

`src/Repositories/Interfaces/QuotesInterface.php`
`src/Repositories/Cache/QuotesCacheRepository.php`
`src/Repositories/Data/QuotesDataRepository.php`
`src/Controller/QuoteController.php`

Important modifications are made to:
`config/services.yaml`

## Tests
To run tests:

1. `cd ipresence-symfony`
2. `php bin/phpunit`

Tests are located at `tests/Unit/Controller/QuoteControllerTest.php`.

## Notes

* This project do not contains any UI.
* Some of dummy Example files provided by framework are not deleted.
* Some unnecessary plugins and configurations are not removed.

    