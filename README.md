# Laravel Todo API

A RESTful API for managing todos, built with Laravel 10.

## Features

- CRUD operations for todos (Create, Read, Update, Delete)
- Filterable, sortable, and searchable todo list
- Status management (not_started, in_progress, completed)
- Comprehensive API documentation
- Unit and Feature tests

## API Endpoints

### List Todos
```
GET /api/todos
```
Query Parameters:
- `status`: Filter by status (not_started/in_progress/completed)
- `search`: Search in title and details
- `sort_by`: Sort by field (id/title/status/created_at)
- `sort_order`: Sort direction (asc/desc)

### Create Todo
```
POST /api/todos
```
Body Parameters:
- `title`: string (required)
- `details`: string (optional)
- `status`: string (required) [not_started/in_progress/completed]

### Update Todo
```
PUT /api/todos/{id}
```
Body Parameters:
- `title`: string (optional)
- `details`: string (optional)
- `status`: string (optional)

### Delete Todo
```
DELETE /api/todos/{id}
```

## Installation

1. Clone the repository
```bash
git clone https://github.com/your-username/todo-app.git
cd todo-app
```

2. Install dependencies
```bash
composer install
```

3. Create environment file
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Configure your database in `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations
```bash
php artisan migrate
```

7. Start the development server
```bash
php artisan serve
```

## Testing

Run the test suite:
```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
