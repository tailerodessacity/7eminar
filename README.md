### About the Project
This project is built with Laravel 12, a modern PHP framework designed for building robust web applications with expressive and elegant syntax. The application follows clean architecture principles and utilizes Docker for containerized development. It includes support for:

- MySQL 8 as the primary database
- Redis for queue
- Nginx as the web server
- PHPUnit for testing
- Sentry for logging
- Laravel Pusher
- Laravel Echo
- Swagger for API documentation (`/api/documentation`)

Make sure Docker and Docker Compose are installed before running the project.

## Installation
Test project for the company

1. Clone this repository:
```
https://github.com/tailerodessacity/7eminar.git
```
2. Go to 'project' directory:
```
cp .env.example .env
```
3. Run your containers:
```
docker-compose up -d --build
```
4. Run your containers:
```
docker exec -ti laravel_app bash
```
5. Run following commands:
```
composer install
```
6. Run following commands:
```
php artisan key:generate
php artisan migrate
```
7. Run following commands:
```
php artisan db:seed
php artisan test
```

## Swagger
The API documentation is available at:
```
http://localhost/api/documentation
```
#### Description
```
This project uses Swagger (OpenAPI) to document the REST API. 
```

#### Update Documentation
```
php artisan l5-swagger:generate

```
Make sure the l5-swagger package is installed and configured in your Laravel project.
