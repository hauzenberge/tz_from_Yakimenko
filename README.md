## Installation

- Clone the repository.
- Run composer install to install the dependencies
- Create a copy of the .env.example file and rename it to .env
- Configure the database connection settings in the .env file
- Run php artisan key:generate to generate an application key
- Run php artisan migrate --seed to run the database migrations and seed the database with initial data
- Start the server using php artisan serve
- Open the application in your browser at http://localhost:8000
