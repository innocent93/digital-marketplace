# Digital Marketplace API

A secure, production-grade RESTful API for creators to upload and sell digital products (e-books, videos, files, etc.), built with **Laravel 11**, **PostgreSQL**, **Redis**, **Sanctum**, and fully Dockerized.

## Features

- Role-based authentication (Creator & Customer) using Laravel Sanctum
- Public product discovery with filtering, sorting, and pagination
- Creator product management (CRUD, file upload/replace, soft-delete)
- Customer purchase (idempotent checkout), library, and secure file download
- Temporary signed URLs for downloads (30-minute expiry, buyer-only)
- Event-driven architecture: purchase triggers queued notification job (Redis)
- Laravel Horizon for queue monitoring (bonus)
- Full OpenAPI/Swagger documentation (via Scribe)
- 18+ Pest feature tests covering auth, CRUD, authorization, idempotency, downloads
- One-command Docker setup with auto-migrations & seeders

## Local Setup (One Command)

```bash
cp .env.example .env
docker-compose up -d --build