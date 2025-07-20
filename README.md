# User Management

A Laravel 12-based backend system for managing users and posts with secure JWT authentication, view tracking with IP-based filtering, and ranked user listing. The project is Dockerized, modular, and testable.

## Features
- **JWT Authentication** – Login with mobile and password to get secure access tokens
- **Post Management** – Create and list posts with view counters
- **View Tracking** – Smart IP-based view counting to prevent abuse
- **User Ranking** – List users by total views on their posts
- **Profile Image Upload** – API to set and store profile image
- **Laravel Horizon** – Queue monitoring interface
- **Swagger (OpenAPI)** – Auto-generated API documentation
- **Redis Caching** – Prevent repeated views from same IP
- **Dockerized Setup** – Full environment (PHP, MySQL, Redis, Nginx)
- **Makefile Automation** – Easy CLI for setup, deploy, testing
- **Test Suite** – Clean unit tests to validate logic

## Requirements
- **Docker**
- **Docker Compose**
- **Make** (optional)

## How to use
### Clone the Repository
```bash
git clone git@github.com:mjpakzad/user-management.git
```
### Change directory
```bash
cd user-management
```

### Using the Makefile (Recommended)
Everything is automated for you:
1. **Initial Setup**
```
make setup
```
This will:

- Copy ```.env.example``` to ```.env```
- Build and launch Docker containers
- Install PHP dependencies
- Generate the application key
- Run migrations and seed the database
2. **Start Services**
```
make up
```
3. **Stop Services**
```
make down
```
4. **Rebuild Services**
```
make rebuild
```
5. **Run Tests**
```
make test
```
6. **Run Artisan Commands**
```
make artisan migrate
```
7. **Run Composer Commands**
```
make composer require vendor/package-name
```
8. **View Logs**
```
make logs
```
9. **Stay Updated**
```
make pipeline
```
   To keep your project up to date, this command will:
- Install PHP dependencies
- Run migrations and seed the database
### Without Using the Makefile
1. **Copy the ```.env``` File**
```
cp .env.example .env
```
2. **Build and Start the Docker Services**
```
docker compose up -d --build
```
3. **Install Composer Dependencies**
```
docker compose exec user-management composer install
```
4. **Generate the Application Key**
```
docker compose exec user-management php artisan key:generate
```
5. **Run Migrations and Seed the Database**
```
docker compose exec user-management php artisan migrate --seed
```
6. Run Tests
```
docker compose exec user-management php artisan test
```
### Accessing the Application
- Application URL: http://localhost
- Laravel Telescope: http://localhost/telescope
- Laravel Horizon: http://localhost/horizon

## Additional Information
### Queue Management
- Queue jobs run in the background using a separate `queue` container
- Redis is used as the queue driver.
- Horizon provides real-time monitoring

### Redis Configuration
- Make sure the following is set in your `.env`
```
REDIS_CLIENT=phpredis
```

### Manual Commands
If you’re not using Makefile, you can still run Artisan or Composer commands:
- **Run Artisan:**
```
docker exec -it user-management php artisan {command}
```
- **Run Composer:**
```
docker exec -it user-management composer {command}
```
