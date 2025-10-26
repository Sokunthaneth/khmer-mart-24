# Cart API Documentation

## Overview
The Cart API provides session-based cart management functionality for the KhmerMart24 e-commerce application. The cart system properly handles product pricing through ProductSku relationships and includes stock validation.

## Authentication Required
**All cart endpoints require authentication using Laravel Sanctum tokens.** Include the Authorization header with your requests:

```bash
Authorization: Bearer {your-token-here}
```

## Fixed Issues
1. **Session Middleware**: Added session middleware to API routes to ensure cart data persists across requests
2. **Price Access**: Fixed price retrieval to use ProductSku model instead of non-existent Product price field
3. **Stock Validation**: Implemented proper stock checking using ProductSku quantity field
4. **Enhanced Remove Function**: Added support for partial quantity removal from cart

## API Endpoints

### 1. Get Cart Contents
**GET** `/api/cart`

Returns all items in the cart with calculated totals.

**Response:**
```json
{
  "items": [
    {
      "id": 1,
      "name": "Product Name",
      "price": 25.99,
      "quantity": 2,
      "subtotal": 51.98,
      "available_stock": 10
    }
  ],
  "total": 51.98
}
```

### 2. Add Product to Cart
**POST** `/api/cart/add/{id}`

**Parameters:**
- `quantity` (optional): Number of items to add (default: 1)

**Response:**
```json
{
  "message": "Product added to cart successfully",
  "product": {
    "id": 1,
    "name": "Product Name",
    "quantity_in_cart": 2,
    "price": 25.99
  },
  "cart_item_count": 5
}
```

### 3. Update Cart Item Quantity
**PATCH** `/api/cart/update/{id}`

**Parameters:**
- `quantity` (required): New quantity for the item

**Response:**
```json
{
  "message": "Cart updated successfully",
  "product": {
    "id": 1,
    "name": "Product Name",
    "quantity_in_cart": 5,
    "price": 25.99
  },
  "cart_item_count": 8
}
```

### 4. Remove from Cart (Enhanced)
**POST** `/api/cart/remove/{id}`

**Parameters:**
- `quantity` (optional): Number of items to remove. If not specified or <= 0, removes entire item

**Response (Partial Removal):**
```json
{
  "message": "Removed 2 item(s) from cart. 3 remaining",
  "cart_item_count": 6,
  "remaining_quantity": 3
}
```

**Response (Complete Removal):**
```json
{
  "message": "Product completely removed from cart",
  "cart_item_count": 3,
  "remaining_quantity": 0
}
```

### 5. Get Cart Item Count
**GET** `/api/cart/count`

**Response:**
```json
{
  "count": 5
}
```

### 6. Clear Cart
**POST** `/api/cart/clear`

**Response:**
```json
{
  "message": "Cart cleared successfully"
}
```

## Features

### Stock Validation
- All operations validate against available ProductSku quantity
- Prevents adding more items than available stock
- Shows clear error messages with available vs requested quantities

### Session Management
- Cart data persists across API requests using Laravel sessions
- Session middleware properly configured for API routes
- Cart data stored as `[product_id => quantity]` array

### Price Calculation
- Prices retrieved from ProductSku model relationships
- Automatic subtotal and total calculations
- Returns formatted price information in responses

### Error Handling
- Proper HTTP status codes (400 for bad requests, 404 for not found)
- Descriptive error messages
- Stock availability validation

## Testing
Comprehensive test suite covers:
- Adding products to cart
- Viewing cart contents with proper pricing
- Complete and partial item removal
- Stock validation scenarios
- Cart count and clearing functionality
- Update quantity operations

All tests pass with 65 assertions covering the full functionality.

## Authentication & Authorization

### Required Authentication
All cart endpoints require authentication via Laravel Sanctum. Make sure to:

1. **Login first** to get an access token:
```bash
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "password"
}
```

2. **Include the token** in subsequent requests:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     http://localhost:8000/api/cart
```

### Error Responses
**401 Unauthorized** - Missing or invalid token:
```json
{
  "message": "Unauthenticated."
}
```

**403 Forbidden** - Token expired or revoked:
```json
{
  "message": "Your session has expired. Please login again."
}
```

## Technical Notes
- Uses Laravel sessions for cart persistence
- Leverages Product-ProductSku relationships for pricing
- Implements proper middleware configuration for API sessions
- Follows RESTful API conventions
- Includes comprehensive error handling and validation
