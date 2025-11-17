Laravel Passport API is a backend API project built with Laravel 12, using Laravel Passport for robust OAuth2 authentication. It provides a standard email/password registration and login system, as well as social authentication with Google and GitHub using Laravel Socialite.Authenticated users can access protected endpoints, such as viewing their own user profile. The entire API is designed to be consumed by a frontend application or tested thoroughly using an API client like Postman.FeaturesLaravel Passport: Uses OAuth2 for secure API authentication.Standard Auth: Endpoints for user registration, login, and logout with email/password.Social Authentication: Login/register with:GoogleGitHubProfile Management: Authenticated endpoint to fetch the user's profile.Token-Based: Returns a Passport access token upon successful authentication.Postman Ready: All endpoints are configured for testing via Postman.PrerequisitesBefore you begin, ensure you have the following installed:PHP >= 8.1ComposerA database (e.g., MySQL, PostgreSQL, SQLite)Postman (for testing)Installation & SetupClone the repository:git clone [https://github.com/your-username/Laravel-Passport-Api.git](https://github.com/your-username/Laravel-Passport-Api.git)
cd Laravel-Passport-Api
Install dependencies:composer install
Create your environment file:cp .env.example .env
Generate application key:php artisan key:generate
Configure your .env file:Update the following variables in your .env file.Database:DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_passport_api
DB_USERNAME=root
DB_PASSWORD=your_db_password
App URL:APP_URL=http://localhost:8000
Socialite Credentials:You must get developer credentials from Google and GitHub.# Google Credentials
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=${APP_URL}/api/auth/google/callback

# GitHub Credentials
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=${APP_URL}/api/auth/github/callback
Note: When creating your credentials, make sure the authorized redirect URIs match what you have in your .env file (e.g., http://localhost:8000/api/auth/google/callback).Run database migrations:php artisan migrate
Install Laravel Passport:This command will create encryption keys and the "Password Grant Client" for standard email/password login.php artisan passport:install
After running this, take note of the Password grant client ID and secret. You will need these for Postman. They are usually id: 2.Start the server:php artisan serve
Your API will be running at http://localhost:8000.API Endpoints & Postman TestingYou can import this project's routes into Postman, or test them manually as described below.Headers: For all authenticated requests, you must include:Authorization: Bearer YOUR_ACCESS_TOKENAccept: application/json1. Standard AuthenticationRegisterEndpoint: POST /api/auth/registerBody: raw (JSON){
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
Login (Get Access Token)This endpoint uses the Passport /oauth/token route.Endpoint: POST /oauth/tokenBody: x-www-form-urlencodedgrant_type: passwordclient_id: 2 (Or the ID of your 'Password grant client' from oauth_clients table)client_secret: Your_password_grant_client_secretusername: test@example.compassword: password123Response:{
    "token_type": "Bearer",
    "expires_in": 31536000,
    "access_token": "...",
    "refresh_token": "..."
}
LogoutEndpoint: POST /api/auth/logoutAuth: Bearer Token (Paste your access_token)Response: 200 OK with a "Logged out" message.2. Social Authentication (Google & GitHub)The OAuth flow is browser-based. You cannot test the full social auth flow directly in Postman.Step 1: Get Redirect URLEndpoint:GET /api/auth/google/redirectGET /api/auth/github/redirectAction: Open one of these URLs in your browser (not Postman).Response: You will be redirected to Google or GitHub to authorize the application.Step 2: Handle CallbackEndpoint:GET /api/auth/google/callbackGET /api/auth/github/callbackAction: After authorizing in your browser, you will be redirected to this callback URL. The API will process the request, create/log in the user, and return a JSON response containing the access token.Response (in browser):{
    "message": "Login successful",
    "user": {
        "id": 3,
        "name": "Social User",
        "email": "social@gmail.com",
        ...
    },
    "token": "your_new_access_token_..."
}
Step 3: Use the Token in PostmanCopy the token from the JSON response in your browser.Go back to Postman.You can now use this token to access protected routes.3. Protected RoutesGet User ProfileEndpoint: GET /api/userAuth: Bearer Token (Paste your access_token from either the standard login or social auth flow).Response:{
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "email_verified_at": null,
    "created_at": "2023-10-27T00:00:00.000000Z",
    "updated_at": "2023-10-27T00:00:00.000000Z"
}
LicenseThis project is open-sourced software licensed under the MIT license.