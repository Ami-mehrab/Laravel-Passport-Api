const API_BASE_URL = 'http://localhost:8000/api'; // Update with your Laravel API URL

// Function to get the API token from localStorage
function getToken() {
    return localStorage.getItem('api_token');
}

// Function to get user details from localStorage
function getUser() {
    return JSON.parse(localStorage.getItem('user'));
}

// Function to check if the user is authenticated
function isAuthenticated() {
    return getToken() !== null;
}

// Function to handle the logout process
function logout() {
    localStorage.removeItem('api_token');
    localStorage.removeItem('user');
    window.location.href = 'login.html';
}
