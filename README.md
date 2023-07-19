# Simple Blog Project

This project is a simple blog application where an authenticated user can perform CRUD (Create, Read, Update, Delete) operations on blog posts and comments. Non-authenticated users can only view the posts and comments.

## Tech Stack

- Back-End: Laravel, Laravel Passport
- Database: MySQL
- Front-End: HTML, CSS, JS, Bootstrap
- Templating: Blade Template Engine
- Authentication: Laravel Passport

## Features

1. **Authenticated User**
    - Create, update and delete blog posts.
    - Create, update and delete comments on blog posts.

2. **Non-authenticated User**
    - View blog posts and their associated comments.

## Code Standards

The project follows PHP code standards to ensure readability, maintainability, and high-quality code.

## Tests

Unit tests have been written to ensure the functionality of the application.

## Installation

Clone the repository:

```
git clone https://github.com/yourusername/your-repo-name.git
```

Navigate into the directory:

```
cd your-repo-name
```

Install composer dependencies:

```
composer install
```

Set up your `.env` file to connect to your database, and set your `APP_URL`:

```bash
cp .env.example .env
```

Generate an app key:

```
php artisan key:generate
```

Run the migrations:

```
php artisan migrate
```

Install Laravel Passport:

```
php artisan passport:install
```

And finally, start your application:

```
php artisan serve
```

Now, you should be able to visit the application at `http://localhost:8000`.

## API Endpoints

This project also provides API endpoints for managing blog posts and comments. 

### Authentication

- `POST /register`: Register a new user
- `POST /login`: Login a user

### Posts 

- `POST /posts`: Create a new post (Auth required)
- `PUT /posts/{id}`: Update a specific post (Auth required)
- `DELETE /posts/{id}`: Delete a specific post (Auth required)
- `GET /posts/{post}`: Retrieve a specific post

### Comments

- `POST /comments`: Create a new comment (Auth required)
- `PUT /comments/{id}`: Update a specific comment (Auth required)
- `DELETE /comments/{id}`: Delete a specific comment (Auth required)
- `GET /comments`: Retrieve all comments (Auth required)

Note that actions that require authentication are protected by `auth:api` middleware.
