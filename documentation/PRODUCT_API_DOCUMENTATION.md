# Product API Documentation

## Overview
The Product API provides comprehensive CRUD (Create, Read, Update, Delete) operations for managing products in the KhmerMart24 e-commerce application. The API includes features like search, filtering, pagination, and proper validation.

## Authentication
- **Public Endpoints**: `GET /api/products`, `GET /api/products/{id}`
- **Protected Endpoints**: `POST`, `PATCH`, `DELETE` operations require authentication using Sanctum token

## API Endpoints

### 1. List Products
**GET** `/api/products`

Retrieve a paginated list of all products with optional filtering and search capabilities.

**Query Parameters:**
- `search` (optional): Search products by name
- `sort_by` (optional): Sort criteria (currently supports "price")
- `sort_direction` (optional): Sort direction ("asc" or "desc", default: "asc")
- `per_page` (optional): Number of items per page (default: 10)
- `page` (optional): Page number for pagination

**Example Requests:**
```bash
# Get all products (paginated)
GET /api/products

# Search products by name
GET /api/products?search=laptop

# Sort by price (ascending)
GET /api/products?sort_by=price&sort_direction=asc

# Pagination
GET /api/products?page=2&per_page=5
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "slug": "product-name",
      "sku": "PROD-001",
      "price": "25.99",
      "stock": 100,
      "description": "Product description",
      "category": {
        "id": 1,
        "name": "Category Name",
        "slug": "category-name"
      },
      "created_at": "2025-10-10T10:00:00.000000Z",
      "updated_at": "2025-10-10T10:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "last_page": 5,
  "per_page": 10,
  "total": 50,
  "links": {
    "first": "http://example.com/api/products?page=1",
    "last": "http://example.com/api/products?page=5",
    "prev": null,
    "next": "http://example.com/api/products?page=2"
  }
}
```

### 2. Get Single Product
**GET** `/api/products/{id}`

Retrieve detailed information about a specific product.

**Parameters:**
- `id` (required): Product ID

**Response (Success):**
```json
{
  "id": 1,
  "name": "Product Name",
  "slug": "product-name",
  "sku": "PROD-001",
  "price": "25.99",
  "stock": 100,
  "description": "Detailed product description",
  "category": {
    "id": 1,
    "name": "Category Name",
    "slug": "category-name"
  },
  "created_at": "2025-10-10T10:00:00.000000Z",
  "updated_at": "2025-10-10T10:00:00.000000Z"
}
```

**Response (Not Found):**
```json
{
  "message": "Product not found"
}
```
*Status: 404 Not Found*

### 3. Create Product
**POST** `/api/products`

Create a new product. Requires authentication.

**Request Body:**
```json
{
  "name": "New Product Name",
  "description": "Product description",
  "summary": "Short product summary",
  "cover": "https://example.com/image.jpg",
  "category_id": 1
}
```

**Validation Rules:**
- `name`: Required, string, max 255 characters
- `description`: Required, string
- `summary`: Optional, string
- `cover`: Optional, string (URL)
- `category_id`: Required, must exist in categories table

**Response (Success):**
```json
{
  "id": 2,
  "name": "New Product Name",
  "slug": "new-product-name",
  "sku": "PROD-002",
  "price": null,
  "stock": 0,
  "description": "Product description",
  "category": {
    "id": 1,
    "name": "Category Name",
    "slug": "category-name"
  },
  "created_at": "2025-10-10T10:00:00.000000Z",
  "updated_at": "2025-10-10T10:00:00.000000Z"
}
```
*Status: 201 Created*

**Response (Validation Error):**
```json
{
  "message": "Validation error",
  "errors": {
    "name": ["The name field is required."],
    "category_id": ["The selected category id is invalid."]
  }
}
```
*Status: 422 Unprocessable Entity*

### 4. Update Product
**PATCH** `/api/products/{id}`

Update an existing product. Requires authentication.

**Parameters:**
- `id` (required): Product ID

**Request Body (all fields optional):**
```json
{
  "name": "Updated Product Name",
  "description": "Updated description",
  "summary": "Updated summary",
  "cover": "https://example.com/new-image.jpg",
  "category_id": 2
}
```

**Validation Rules:**
- `name`: Optional, string, max 255 characters
- `description`: Optional, string
- `summary`: Optional, string
- `cover`: Optional, string (URL)
- `category_id`: Optional, must exist in categories table

**Response (Success):**
```json
{
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Product Name",
    "slug": "updated-product-name",
    "sku": "PROD-001",
    "price": "25.99",
    "stock": 100,
    "description": "Updated description",
    "category": {
      "id": 2,
      "name": "New Category Name",
      "slug": "new-category-name"
    },
    "created_at": "2025-10-10T10:00:00.000000Z",
    "updated_at": "2025-10-10T12:00:00.000000Z"
  }
}
```
*Status: 200 OK*

**Response (Not Found):**
```json
{
  "message": "Product not found"
}
```
*Status: 404 Not Found*

### 5. Delete Product
**DELETE** `/api/products/{id}`

Soft delete a product. Requires authentication.

**Parameters:**
- `id` (required): Product ID

**Response (Success):**
*Status: 204 No Content*
(Empty response body)

**Response (Not Found):**
```json
{
  "message": "Product not found"
}
```
*Status: 404 Not Found*

## Features

### Search and Filtering
- **Text Search**: Search products by name using the `search` parameter
- **Sorting**: Sort products by price in ascending or descending order
- **Category Filtering**: Filter products by category (implementation may vary)

### Pagination
- Default pagination with 10 items per page
- Customizable page size using `per_page` parameter
- Standard Laravel pagination response format with navigation links

### Soft Deletes
- Products are soft deleted, not permanently removed
- Deleted products are automatically excluded from listing endpoints
- Maintains data integrity for historical orders and references

### Validation
- Comprehensive input validation for all create/update operations
- Proper error messages for validation failures
- Foreign key validation for category relationships

## Data Relationships

### Product-Category Relationship
- Each product belongs to one category
- Category information is included in product responses
- Category must exist when creating/updating products

### Product-ProductSku Relationship
- Products can have multiple SKUs (Stock Keeping Units)
- SKUs contain pricing and stock information
- Used by cart system for price calculations

## Error Handling

### Common HTTP Status Codes
- `200 OK`: Successful GET/PATCH requests
- `201 Created`: Successful POST requests
- `204 No Content`: Successful DELETE requests
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Insufficient permissions

### Error Response Format
```json
{
  "message": "Error description",
  "errors": {
    "field_name": ["Specific validation error"]
  }
}
```

## Authentication Requirements

### Public Endpoints
- `GET /api/products` - List products
- `GET /api/products/{id}` - Get single product

### Protected Endpoints (Require Sanctum Token)
- `POST /api/products` - Create product
- `PATCH /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

### Usage Example
```bash
# Include Authorization header for protected endpoints
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Content-Type: application/json" \
     -X POST \
     -d '{"name":"New Product","description":"Description","category_id":1}' \
     http://localhost:8000/api/products
```

## Best Practices

### Creating Products
1. Ensure category exists before creating products
2. Provide meaningful descriptions for better user experience
3. Use appropriate image URLs for product covers

### Updating Products
1. Only send fields that need to be updated
2. Validate category changes carefully
3. Consider impact on existing orders/cart items

### Performance Optimization
1. Use pagination for large product catalogs
2. Implement proper indexing on searchable fields
3. Consider caching for frequently accessed products

## Testing
The Product API includes comprehensive test coverage:
- CRUD operations testing
- Validation testing
- Authentication testing
- Pagination testing
- Soft delete functionality
- Search and filtering capabilities

Run tests with:
```bash
php artisan test tests/Feature/ProductControllerTest.php
```
