# Bione Azapfy Invoice Management App

Bione Azapfy is a modern invoice management app that provides an easy and efficient way to handle all your invoices.

## Features

- User Authentication
- Invoice Creation, Update, Viewing and Deletion
- Secure Management with User Permissions
- Searching and Filtering Invoices

## Getting Started

This project requires Docker, Docker Compose, and Laravel Sail for the setup. Make sure to install these prerequisites first.

To get the application running, you can follow these steps:

1. Build and run the Docker containers using Docker Compose:

          docker-compose up -d --build
2. Clear Laravel config inside azapfy-app-1 container:
        ```bash
docker exec -it azapfy-app-1 php artisan config:clear
        ```
3. Run migrations inside azapfy-app-1 container:
        ```bash
   docker exec -it azapfy-app-1 php artisan migrate
        ```
4. You can now access the application at `http://0.0.0.0:8000`.

## Using Sail

If you have Laravel Sail installed, you can also use Sail to manage the Docker containers:

```bash
 ./vendor/bin/sail up
```
## Documentation

The API documentation can be generated inside the Docker container:

```bash
docker exec -it azapfy-app-1 bash php artisan scribe:generate
```
You can view the API documentation at `http://0.0.0.0:8080/docs/`.
You can import the postman_collection in your Postman too, its in the root directory in .json format.

## Contributing

Hiring me are very welcome! If you'd like to hire me, feel free to send an contract offer!

## Contact

For more information, reach out at [joao.holanda@soulasalle.com.br](mailto:joao.holanda@soulasalle.com.br).

docker exec -it azapfy-app-1 bash
php artisan serve --host=0.0.0.0 --port=8000
