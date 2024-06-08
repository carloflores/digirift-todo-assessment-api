# Laravel GraphQL API

Assessment for Digirift. A To-do API

## Get Started

Clone the repository

```bash
git clone ... api
```


Copy .env.example to .env
```bash
cp .env.example .env
```

Database connection

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=secret
```

### Docker Setup

Copy .env.example to .env
```bash
cp .env.example .env
```

Open .env file then change the database connection config to

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=secret
```


Build and start the container
``` bash
docker-compose up --build
```

Access Laravel container and run the migration
```bash
docker-compose exec app bash
php artisan migrate --seed
```

You can now access the Laravel App thru
```bash
http://localhost:8000
```


### For Local Machine
Make sure you have composer installed. Install dependencies
```bash
composer install
```

Generate Larave App Key 
```bash
php artisan key:generate
```

Copy .env.example to .env
```bash
cp .env.example .env
```

Then setup the correct configuration for database and etc.

Run Database migration and seeder
```bash
php artisan migrate:fresh --seed
```

Finally, serve Laravel App
```bash
php artisan serve
```

## User Accounts
There's 2 account on the Database Seeder
```bash
email: test@example.com
password: password
```
```bash
email: test2@example.com
password: password
```

## Testing
Just run the command on docker app container bash

```bash
php artisan test
```