# User Flow Application

## Overview

This project is a user flow application designed to collect data and actions from users based on a specified user process. The process is derived from the [Global Talent Visa Application Checklist](https://technation.io/global-talent-visa/#:~:text=Global-,Talent,-Visa%20Application%20Checklist). The application utilizes Laravel 10, Tailwind CSS, Livewire, Inertia, Laravel Sanctum for authentication, Laravel Breeze for the admin panel, and Laravel Jetstream for the frontend and authentication.

## Features

- **Laravel 10**: The latest version of Laravel is used for building the application.
- **Tailwind CSS**: A utility-first CSS framework for styling the application.
- **Livewire**: A framework for building dynamic interfaces without leaving the comfort of Laravel.
- **Inertia.js**: A framework for building single-page applications using classic server-side routing and controllers.
- **Laravel Sanctum**: Provides a simple authentication system for SPAs (single-page applications).
- **Laravel Breeze**: A minimal and simple starting point for building a Laravel application with authentication.
- **Laravel Jetstream**: A robust application scaffolding for Laravel that includes features like two-factor authentication, session management, and API support.

## Database

The application uses MySQL as the database. Necessary migrations and seeders will be provided to create the required tables to fulfill the user flow based on the checklist.

## Clean Code Architecture

The project is structured to follow clean code architecture principles, utilizing interfaces and inheritance to ensure flexibility, scalability, and maintainability.

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Running the Project

1. **Clone the repository** (if applicable):
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```technation-visa-workflow/README.md

2. **Build and start the Docker containers**:
   ```bash
   docker-compose up -d
   ```

3. **Access the application**:
   Open your web browser and navigate to `http://localhost:8000`.

4. **Install Composer dependencies**:
   If you need to install additional PHP packages, you can access the application container:
   ```bash
   docker exec -it laravel_app bash
   composer install
   ```

5. **Run migrations**:
   Inside the container, run the following command to set up the database:
   ```bash
   php artisan migrate
   ```

6. **Run Vite for asset compilation**:
   To compile your assets using Vite, you can run:
   ```bash
   npm install
   npm run dev
   ```

### Stopping the Project

To stop the Docker containers, run: