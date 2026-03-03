# doc/storage

A simple temporary file storage service. Upload PDF or DOCX files and they are automatically deleted after 24 hours.

## Stack

- **Laravel 12** + Octane (Swoole)
- **PostgreSQL** — database
- **RabbitMQ** — job queue
- **Supervisord** — manages Octane, queue workers, and scheduler

## Features

- Upload PDF/DOCX files up to 10 MB
- Files expire and are deleted automatically after 24 hours via a scheduled command
- Files can also be deleted manually
- Every deletion (manual or expired) dispatches a `DocumentDeleted` job to RabbitMQ, which logs the event to `storage/logs/deletions.log`
- Three pages: **Upload**, **Files**, **Messages**

## Running

```bash
docker compose up -d
```

The app will be available at `http://localhost`.

On first run, copy `.env.example` to `.env` and set your values, then run migrations:

```bash
docker exec -it file_storage_app php artisan migrate
```

## Environment variables

| Variable | Default | Description |
|---|---|---|
| `DB_DATABASE` | `file_storage` | PostgreSQL database name |
| `DB_USERNAME` | `laravel` | PostgreSQL user |
| `DB_PASSWORD` | `secret` | PostgreSQL password |
| `RABBITMQ_USER` | `guest` | RabbitMQ user |
| `RABBITMQ_PASSWORD` | `guest` | RabbitMQ password |
| `RABBITMQ_VHOST` | `/` | RabbitMQ virtual host |

## Artisan commands

```bash
# Manually prune expired documents
php artisan documents:prune
```
