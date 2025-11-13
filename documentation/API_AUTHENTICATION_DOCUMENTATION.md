# KhmerMart24 Authentication API Documentation

This document provides comprehensive information about the authentication APIs implemented using Laravel Sanctum.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication Endpoints

### 1. User Registration
**Endpoint:** `POST /auth/register`

**Description:** Register a new user account

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "Password123!",
    "phone_number": "+1234567890", // optional
    "birth_of_date": "1990-01-01" // optional
}
```

**Validation Rules:**
- `first_name`: required, string, max 255 characters
- `last_name`: required, string, max 255 characters  
- `username`: required, string, max 255 characters, unique
- `email`: required, valid email, max 255 characters, unique
- `password`: required, min 8 characters, must contain uppercase, lowercase, number, and special character
- `phone_number`: optional, valid phone format
- `birth_of_date`: optional, valid date, must be before today

**Success Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "birth_of_date": "1990-01-01",
            "role": "user"
        },
        "access_token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 2. User Login
**Endpoint:** `POST /auth/login`

**Description:** Authenticate user and get access token

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "Password123!"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "birth_of_date": "1990-01-01",
            "role": "user"
        },
        "access_token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### 3. Get Authenticated User
**Endpoint:** `GET /auth/user`

**Description:** Get the current authenticated user's profile

**Headers:**
```
Authorization: Bearer {access_token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "phone_number": "+1234567890",
            "birth_of_date": "1990-01-01",
            "role": "user"
        }
    }
}
```

### 4. User Logout
**Endpoint:** `POST /auth/logout`

**Description:** Logout user and revoke current access token

**Headers:**
```
Authorization: Bearer {access_token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### 5. Logout from All Devices
**Endpoint:** `POST /auth/logout-all`

**Description:** Logout user from all devices by revoking all access tokens

**Headers:**
```
Authorization: Bearer {access_token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logged out from all devices successfully"
}
```

### 6. Forgot Password
**Endpoint:** `POST /auth/forgot-password`

**Description:** Send password reset link to user's email

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Password reset link sent to your email"
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": [
            "No account found with this email address."
        ]
    }
}
```

### 7. Reset Password
**Endpoint:** `POST /auth/reset-password`

**Description:** Reset user password using token from email

**Request Body:**
```json
{
    "token": "reset_token_from_email",
    "email": "john@example.com",
    "password": "NewPassword123!",
    "password_confirmation": "NewPassword123!"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Password reset successfully"
}
```

**Error Response (400):**
```json
{
    "success": false,
    "message": "Failed to reset password. Invalid token or email."
}
```

## Error Responses

### Validation Errors (422)
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "field_name": [
            "Error message 1",
            "Error message 2"
        ]
    }
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Internal server error",
    "error": "Error details"
}
```

## Authentication

The API uses Laravel Sanctum for authentication. After successful login or registration, you'll receive an access token that should be included in the `Authorization` header for protected endpoints:

```
Authorization: Bearer {access_token}
```

## Password Requirements

Passwords must meet the following criteria:
- Minimum 8 characters
- At least one uppercase letter (A-Z)
- At least one lowercase letter (a-z)
- At least one number (0-9)
- At least one special character (@$!%*?&)

## Email Configuration

Password reset emails are sent using the configured mail driver. Make sure to set up your mail configuration in the `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS="noreply@khmermart24.com"
MAIL_FROM_NAME="KhmerMart24"
FRONTEND_URL=http://localhost:3000
```

The `FRONTEND_URL` is used to generate password reset links that redirect to your frontend application.

## Testing

To test the authentication endpoints, you can use the provided test suite:

```bash
php artisan test tests/Feature/AuthenticationTest.php
```

## Example Usage with cURL

### Register a new user:
```bash
curl -X POST http://127.0.0.1:8001/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!"
  }'
```

### Login:
```bash
curl -X POST http://127.0.0.1:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "Password123!"
  }'
```

### Get user profile:
```bash
curl -X GET http://127.0.0.1:8001/api/auth/user \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Accept: application/json"
```
