# Eye Exam Booking

Ophthalmology clinic management: scheduling and prescriptions.

## Running the Project with Laravel Sail

### Prerequisites
- Docker installed on your system
- Composer installed for PHP dependency management

### Installation
1. Clone the repository to your local machine.
2. Navigate to the project directory.
3. Install PHP dependencies:
   ```
   composer install
   ```
4. Copy the environment file and configure it:
   ```
   cp .env.example .env
   ```
   Edit `.env` with your database and other configurations.
5. Start Laravel Sail:
   ```
   ./vendor/bin/sail up
   ```
   This will build and start the Docker containers.

### Running the Application
- Once Sail is running, access the application at `http://localhost`.
- To run database migrations:
  ```
  ./vendor/bin/sail artisan migrate
  ```
- To run tests:
  ```
  ./vendor/bin/sail test
  ```
- To stop Sail:
  ```
  ./vendor/bin/sail down
  ```

### Additional Commands
- View logs: `./vendor/bin/sail logs`
- Access the container shell: `./vendor/bin/sail shell`
- Run Artisan commands: `./vendor/bin/sail artisan <command>`

## API Documentation

This project uses `laravel-request-docs` to automatically generate and present API documentation. Once the application is running, you can access the API documentation at the default URI: `/request-docs`.

To view the documentation:
1. Start the application using Laravel Sail as described above.
2. Navigate to `http://localhost/request-docs` in your web browser.

The documentation will display all available API endpoints, their parameters, and responses based on the request classes and routes defined in the project.