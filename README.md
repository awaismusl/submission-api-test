# Laravel Application

**Setup**

**Instructions:**
* [Initial Setup](#initial-setup)
    * [Environment](#environment)
    * [Configuration](#configuration)
    * [Setup](#setup)
* [Local Development](#local-development)

---

## Initial Setup

### Environment

This application requires a Laravel environment:

* PHP 8.1
* [Laravel 10.x](https://laravel.com/docs/10.x/installation#installation)

### Setup

Clone the repository and copy the `.env.example` file to `.env` and update to match your local database and other configurations.

## Local Development

Run the following commands from the repository's root directory:

Install the Composer dependencies

```bash
composer install
```

Run database migrations and seed

```bash
php artisan migrate
```

Run the unit test

```bash
php artisan test
```

## Considerations

### Queues

Setup Queues Local Development

* Use ```QUEUE_CONNECTION=sync``` in .env file so it will bypass the queue job and run all the code directly in the same web request

* Use ```QUEUE_CONNECTION=database``` in .env which will add a job in the jobs table in the database, now when you run ```php artisan queue:work``` command it will process the jobs

Setup Queues Production

* Use ```QUEUE_CONNECTION=database``` in .env which will add a job in the jobs table in the database, now when you run ```php artisan queue:work``` command through [supervisor](https://laravel.com/docs/9.x/queues#supervisor-configuration) it will process the jobs

Install Supervisor

* sudo apt-get install supervisor

Create configuration file

* nano /etc/supervisor/conf.d/gratofy.conf

Add this content in the file

```bash
[program:gratofy]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/gratofy.xyz/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/gratofy.xyz/storage/logs/worker.log
stopwaitsecs=3600
```

RUN these commands

* sudo supervisorctl reread
* sudo supervisorctl update
* sudo supervisorctl start gratofy :*

You need to restart queue so queue worker will load the latest changes

```bash
php artisan queue:restart
```
