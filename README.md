# Laravel Passport API

A secure, token-based backend API built with Laravel 12 and Passport, providing robust OAuth2 authentication. This project includes standard email/password login, as well as social auth integration for Google and GitHub.

---

### ✨ Features

* **Secure OAuth2 Auth:** Powered by Laravel Passport for token-based authentication.
* **Standard Authentication:** Full-featured registration, login, and logout endpoints.
* **Socialite Integration:** Seamless login and registration with Google and GitHub.
* **Protected Routes:** Example API endpoint for fetching the authenticated user's profile.
* **Postman-Ready:** Fully tested and designed for consumption via API clients like Postman.

---

### 🛠️ Tech Stack

* **Framework:** Laravel 12
* **Authentication:** Laravel Passport
* **Social Auth:** Laravel Socialite
* **Database:** MySQL, PostgreSQL, etc. (via Eloquent)
* **API Testing:** Postman

---

### 🚀 Getting Started

Follow these instructions to get a local copy up and running for development and testing.

#### Prerequisites

* PHP >= 8.1
* Composer
* A compatible database (MySQL, PostgreSQL, etc.)

#### ⚙️ Installation

1.  **Clone the repository:**
    ```sh
    git clone [https://github.com/your-username/your-repository-name.git](https://github.com/your-username/your-repository-name.git)
    cd your-repository-name
    ```

2.  **Install dependencies:**
    ```sh
    composer install
    ```

3.  **Setup environment file:**
    ```sh
    cp .env.example .env
    ```

4.  **Generate application key:**
    ```sh
    php artisan key:generate
    ```

5.  **Configure `.env`:**
    Update your `.env` file with your database credentials and application URL.
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_DATABASE=laravel_passport_api
    DB_USERNAME=root
    DB_PASSWORD=your_db_password

    APP_URL=http://localhost:8000
    ```

6.  **Socialite Configuration:**
    Add your Google and GitHub OAuth credentials to your `.env` file. The redirect URIs must match these values.
    ```ini
    # Google Credentials
    GOOGLE_CLIENT_ID=your_google_client_id
    GOOGLE_CLIENT_SECRET=your_google_client_secret
    GOOGLE_REDIRECT_URI=${APP_URL}/api/auth/google/callback

    # GitHub Credentials
    GITHUB_CLIENT_ID=your_github_client_id
    GITHUB_CLIENT_SECRET=your_github_client_secret
    GITHUB_REDIRECT_URI=${APP_URL}/api/auth/github/callback
    ```

7.  **Database Migration:**
    ```sh
    php artisan migrate
    ```

8.  **Passport Installation:**
    This command creates the necessary encryption keys and Password Grant Client.
    ```sh
    php artisan passport:install
    ```
    > **Note:** Note the `client_id` (usually 2) and `client_secret` for the "Password grant client" for testing.

9.  **Run the server:**
    ```sh
    php artisan serve
    ```
    The API will be accessible at `http://localhost:8000`.

---

### 🧭 API Endpoints

All requests require an `Accept: application/json` header. Protected routes require an `Authorization: Bearer YOUR_ACCESS_TOKEN` header.

#### 1. Standard Authentication

**POST `/api/auth/register`**
Registers a new user.

<details>
<summary>Click to see Request Body (JSON)</summary>

```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
</details>

POST /oauth/token Logs in a user (password grant) and returns an access token.

<details> <summary>Click to see Request Body (x-www-form-urlencoded)</summary>

grant_type: password
client_id: 2
client_secret: Your_password_grant_client_secret
username: test@example.com
password: password123
</details>

<details> <summary>Click to see Success Response (JSON)</summary>

JSON

{
    "token_type": "Bearer",
    "expires_in": 31536000,
    "access_token": "...",
    "refresh_token": "..."
}
</details>

POST /api/auth/logout (Protected) Logs out the authenticated user and revokes their token.

2. Social Authentication
This flow is browser-based.

GET /api/auth/{provider}/redirect (e.g., /api/auth/google/redirect) Redirects the user to the provider's OAuth authorization page.

GET /api/auth/{provider}/callback (e.g., /api/auth/google/callback) Handles the callback from the provider. On success, it returns a JSON response with the user data and a new Passport access token.

<details> <summary>Click to see Success Response (JSON)</summary>

JSON

{
    "message": "Login successful",
    "user": {
        "id": 3,
        "name": "Social User",
        "email": "social@gmail.com",
        "..." : "..."
    },
    "token": "your_new_access_token_..."
}
</details>

3. Protected Routes
GET /api/user (Protected) Fetches the profile of the currently authenticated user.

<details> <summary>Click to see Success Response (JSON)</summary>

JSON

{
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "email_verified_at": null,
    "created_at": "2023-10-27T00:00:00.000000Z",
    "updated_at": "2023-10-27T00:00:00.000000Z"
}
</details>

🤝 Contributing
Contributions are welcome! If you have suggestions for improvements, please fork the repo and create a pull request, or open an issue with the "enhancement" tag.

Fork the Project

Create your Feature Branch (git checkout -b feature/AmazingFeature)

Commit your Changes (git commit -m 'Add some AmazingFeature')

Push to the Branch (git push origin feature/AmazingFeature)

Open a Pull Request

📜 License
This project is open-sourced software licensed under the MIT license.