# Chat App API

A Laravel 12 API for a chat application with real-time messaging using Reverb and Redis.

## Features
- User authentication via phone number
- Create and manage chats
- Send and delete messages
- Real-time updates with Reverb

## Installation
1. Clone the repository: `git clone https://github.com/Abdelrahman928/Chat-app-api.git`
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure database/Redis
4. Run migrations: `php artisan migrate`
5. Start the server: `php artisan serve`

## API Documentation
- View the full interactive documentation at `/docs/api` (local) or [hosted version](#) after deployment.
- Download the OpenAPI JSON spec: [docs/api.json](http://localhost:8000/docs/api.json) (local) or access the hosted file after deployment.

## About Laravel
This project is built on [Laravel](https://laravel.com), a web application framework with expressive, elegant syntax. Laravel provides tools like routing, ORM, and real-time broadcasting, which power this chat API.

## License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
