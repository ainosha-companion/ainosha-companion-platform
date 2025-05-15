# Project Ainosha Client backend service

## Table of Contents

- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Setup Instructions](#setup-instructions)
- [Stopping the Containers](#stopping-the-containers)
- [Following coding standards](#following-coding-standards)
- [Super Admin Setup](#super-admin-setup)
- [Webhook API](#webhook-api)
- [API Key Authentication](#api-key-authentication)

## Introduction

This project is a backend service for the Ainosha Client project. It is built using the Laravel framework.

## Prerequisites

Make sure you have the following installed on your machine:
- Docker
- Docker Compose

## Setup Instructions

1. **Clone the repository:**
    ```sh
    git clone <repository-url>
    cd <repository-directory>
    ```

2. **Copy the `.env` file:**
    ```sh
    cp .env.example .env
    ```

3. **Update the `.env` file:**
   Edit the `.env` file and set the necessary environment variables, especially the database credentials.

4. **Build and start the containers:**
    ```sh
    docker-compose up --build -d
    ```

5. **Install PHP dependencies:**
    ```sh
    docker-compose exec aino_app composer install
    ```

6. **Run database migrations:**
    ```sh
    docker-compose exec aino_app php artisan migrate
    ```

7. **Generate app key:**
    ```sh
    docker-compose exec aino_app php artisan key:generate
   ```

8**Access the application:**
   Open your browser and navigate to `http://localhost:8000`.

## Stopping the Containers

To stop the running containers, use:
```sh
docker-compose down
```

## Following coding standards
https://docs.google.com/document/d/10e-38CmKySEThoabx_IN1ayIodRjeHhaaLynpI43SbI/edit?tab=t.0#heading=h.qohc41369rbo

## Super Admin Setup

To create a super_admin role and assign it to a user, run the following command:

```bash
php artisan app:create-super-admin
```

This command will:
1. Create a 'super_admin' role with all available permissions
2. Create a super admin user with email 'superadmin2902@test.test' if it doesn't exist
3. Assign the super_admin role to this user

The default password for the super admin user is 'defaultPW2902'. It's recommended to change this password after the initial setup.

## Webhook API

The application provides a webhook API for creating content programmatically from external sources. This API uses API key authentication instead of user authentication.

### Setting Up the Webhook User

To use the webhook API, you need to set up a webhook user and generate an API key:

```bash
# Create the webhook user
php artisan app:create-webhook-user

# Generate an API key
php artisan api:key:generate
```

The webhook user will be created with the super_admin role, allowing it to perform all operations in the CMS.

For detailed documentation on the webhook API endpoints and usage, see [Webhook API Documentation](docs/webhook-api.md).

## API Key Authentication

The application supports API key authentication for certain endpoints. This allows external systems to access the API without user credentials.

### Generating an API Key

To generate an API key, run:

```bash
php artisan api:key:generate
```

This will generate a new API key and display it in the console. Make sure to save this key as it will not be displayed again.

### Using API Keys

API keys can be included in requests using either:

1. The `X-API-Key` header (recommended)
2. The `api_key` query parameter

For more details on API key authentication, see [Webhook API Documentation](docs/webhook-api.md#authentication).
