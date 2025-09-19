# KhmerMart24 - E-commerce Backend API

A robust Laravel-based e-commerce backend featuring user authentication, product management, order processing, and admin tools.

[![PHP CI](https://github.com/Sokunthaneth/khmer-mart-24/actions/workflows/php.yml/badge.svg)](https://github.com/Sokunthaneth/khmer-mart-24/actions/workflows/php.yml)

## Week 6 - Deployment & Admin Tools

This project has been enhanced for production deployment with:
- ✅ Production-ready configuration
- ✅ Admin inventory management system
- ✅ Comprehensive test suite
- ✅ CI/CD pipeline with GitHub Actions
- ✅ Health check endpoints
- ✅ Security middleware and logging

## Features

### Core E-commerce
- **Products & Categories**: Full CRUD with relationships and caching
- **User Authentication**: Registration, login with secure sessions
- **Shopping Cart**: Session-based cart management
- **Order Processing**: Complete order lifecycle with stock management
- **Inventory Tracking**: Real-time stock updates and validation

### Admin Tools
- **Inventory Management**: Update individual or bulk product stock
- **Order Management**: View statistics and update order status
- **Admin Dashboard**: Protected admin routes with middleware
- **Audit Logging**: Track all admin actions

### Production Features
- **Health Monitoring**: `/health` endpoint for deployment verification
- **Caching Layer**: Redis/database caching for performance
- **Security**: Admin middleware, CSRF protection, secure headers
- **Testing**: Comprehensive PHPUnit test suite
- **CI/CD**: Automated testing and deployment checks

## API Endpoints

### Public Routes
```
GET /api/health                    # Health check
GET /api/products                  # List products (paginated)
GET /api/products/{id}             # Get product details
GET /api/categories                # List categories
```

### Authenticated Routes
```
GET /api/orders                    # User's orders
POST /api/orders                   # Create order from cart
GET /api/orders/{id}               # Order details
PATCH /api/orders/{id}/cancel      # Cancel pending order
```

### Admin Routes (Protected)
```
GET /api/admin/products                        # List all products
GET /api/admin/products/{id}                   # Product details with stats
PATCH /api/admin/products/{id}/inventory       # Update stock
PATCH /api/admin/products/bulk-inventory       # Bulk stock update
PATCH /api/admin/orders/{id}/status           # Update order status
GET /api/admin/orders/statistics              # Order statistics
```

## Installation & Setup

### Local Development
```bash
# Clone repository
git clone https://github.com/Sokunthaneth/khmer-mart-24.git
cd khmer-mart-24

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Production Deployment

#### 1. VPS/Server Deployment
```bash
# Clone to server
git clone https://github.com/Sokunthaneth/khmer-mart-24.git /var/www/khmermart24
cd /var/www/khmermart24

# Production setup
cp .env.example .env
# Edit .env with production values

# Run deployment script
./deploy.sh
```

#### 2. Platform-as-a-Service (Render/Fly.io/Heroku)
The app is configured for PaaS deployment with:
- Automatic build optimization
- Environment variable configuration
- Health check endpoints at `/health`

### Environment Configuration

Key production environment variables:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
CACHE_STORE=redis
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

## Testing

### Run Test Suite
```bash
# All tests
php artisan test

# Specific test types
php artisan test tests/Feature/CartOrderTest.php
php artisan test tests/Unit/AdminInventoryTest.php

# With coverage
php artisan test --coverage
```

### Test Categories
- **Unit Tests**: Admin inventory management
- **Feature Tests**: Cart/order workflows, API endpoints
- **Integration Tests**: Database relationships and caching

## Deployment Checklist

- [ ] Environment variables configured
- [ ] Database migrations run
- [ ] SSL certificate installed
- [ ] Health check returns 200 OK
- [ ] Admin endpoints protected (403 for non-admin)
- [ ] Tests passing in CI
- [ ] Caching optimized
- [ ] Error logging configured
- [ ] Backup strategy implemented

## Admin Access

To create an admin user:
```bash
php artisan tinker
>>> $user = User::find(1); // or create new user
>>> $user->is_admin = true;
>>> $user->save();
```

## Monitoring & Troubleshooting

### Health Check
```bash
curl -s https://your-domain.com/health
# Expected: {"status":"ok","timestamp":"...","environment":"production","version":"1.0.0"}
```

### Common Issues
- **502 Bad Gateway**: Check PHP-FPM socket configuration
- **Permission Denied**: Ensure `storage/` and `bootstrap/cache/` are writable
- **Missing Environment**: Verify `.env` file and `APP_KEY`

## Architecture

### Security Layers
1. **Authentication**: Laravel Sanctum for API tokens
2. **Authorization**: Admin middleware for protected routes
3. **Validation**: Request validation for all inputs
4. **Logging**: Admin action audit trail

### Performance Optimizations
1. **Caching**: Query results and configurations
2. **Optimization**: Production asset compilation
3. **Database**: Proper indexing and relationships
4. **Sessions**: Database-backed session storage

## Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -am 'Add new feature'`
4. Push branch: `git push origin feature/new-feature`
5. Create Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
