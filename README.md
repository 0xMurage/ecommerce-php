# Simple PHP E-commerce API

API documentation available [here](https://mimidotsuser.github.io/ecommerce-php/)

### Development setup

#### Prerequisites

- MySQL compatible database server
- PHP 7.4+
- [Composer](https://getcomposer.org/)
- GIT

##### How to set up

1) Clone the project `git clone https://github.com/mimidotsuser/ecommerce-php.git`
2) Install the dependencies `composer install`
3) Copy `.env-example` to `.env` `cp .env-example .env`
4) Generate JWT secret key   `php artisan jwt:secret`
5) Update the database credentials on the `.env` file.
6) Run database migrations `php artisan migrate`
7) Seed the database with dummy data `php artisan db:seed`
8) Run the server `php -S localhost:80 -t public`

**Note**: If you want to use Docker, ensure you have [docker compose](https://docs.docker.com/compose/) installed.

## License

The project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
