# KhmerMart24 - E-commerce Backend API & Frontend Storefront

A robust Laravel-based e-commerce backend with HTML5/Tailwind CSS frontend, featuring user authentication, product management, order processing, and admin tools.

[![PHP CI](https://github.com/Sokunthaneth/khmer-mart-24/actions/workflows/php.yml/badge.svg)](https://github.com/Sokunthaneth/khmer-mart-24/actions/workflows/php.yml)

## 🎯 Week 7 - Frontend: HTML/CSS for Store Layout

### Project Overview
Building a static storefront with HTML5 and Tailwind CSS—responsive product grids and storefront scaffolding for KhmerMart24.

### 📋 Week 7 Objectives
- ✅ Structure KhmerMart24 pages with semantic HTML5 (header, main, footer)
- ✅ Build responsive product grid and layout using Tailwind CSS
- ✅ Establish a reusable design system (colors, spacing, typography)
- ✅ Ensure mobile-first, accessible markup

### 🎯 Week 7 Outcomes
- Static homepage and product list page with responsive grid
- Basic Tailwind configuration and utility classes in use
- Clean, semantic markup ready for Week 8 JavaScript interactivity

### 📅 Week 7 Schedule
- **Monday (2.5h)**: Lecture + live demos (HTML structure, Tailwind setup, product grid)
- **Tuesday (2.5h)**: Self-directed storefront build
- **Friday (1h)**: Presentation of static layout and UX rationale

### ✅ Week 7 Deliverables Checklist
- [x] Semantic HTML structure for homepage and product list
- [x] Tailwind CSS integrated and configured
- [x] Responsive product grid
- [x] Accessibility basics (landmarks, alt text, contrast)
- [x] Mobile-first responsive design
- [x] Clean design system with reusable components

## 🚀 Quick Start - Frontend Development

### Prerequisites
- Node.js + npm installed
- Basic understanding of HTML/CSS
- Assets (logo/placeholders) ready

### Frontend Setup
```bash
# Navigate to project root
cd khmermart24

# Initialize npm and install Tailwind CSS
npm init -y
npm install -D tailwindcss postcss autoprefixer

# Initialize Tailwind configuration
npx tailwindcss init -p

# Start the build process
npx tailwindcss -i ./frontend/input.css -o ./public/output.css --watch
```

### Development Workflow
```bash
# Backend (Laravel API)
docker-compose up -d

# Frontend (Tailwind CSS)
npx tailwindcss -i ./frontend/input.css -o ./public/output.css --watch

# Open storefront
open frontend/index.html
```

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

### Docker Deployment (Recommended)

#### Quick Start with Docker
```bash
# Clone repository
git clone https://github.com/Sokunthaneth/khmer-mart-24.git
cd khmer-mart-24

# One-command setup for development
./docker-setup.sh

# Access application at http://localhost:8000
```

#### Production Docker Build
```bash
# Build production image
./docker-build.sh v1.0.0

# Run production container
docker run -d \
  --name khmermart24-prod \
  -p 80:80 \
  --env-file .env.production \
  khmermart24:v1.0.0

# Health check
curl http://localhost/health
```

#### Docker Compose Development
```bash
# Start development environment
docker-compose up -d

# View logs
docker-compose logs -f app

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan test

# Stop services
docker-compose down
```

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

#### 1. Docker Container Deployment (Recommended)
```bash
# Build production image
./docker-build.sh v1.0.0

# Deploy to production server
docker run -d \
  --name khmermart24 \
  --restart unless-stopped \
  -p 80:80 \
  -p 443:443 \
  --env-file .env.production \
  -v /path/to/ssl:/etc/ssl/certs \
  khmermart24:v1.0.0
```

#### 2. Container Registry Deployment
```bash
# Push to registry
REGISTRY=your-registry.com ./docker-build.sh v1.0.0

# Deploy from registry
docker run -d \
  --name khmermart24 \
  --restart unless-stopped \
  -p 80:80 \
  --env-file .env.production \
  your-registry.com/khmermart24:v1.0.0
```

#### 3. Kubernetes Deployment
```yaml
# k8s-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: khmermart24
spec:
  replicas: 3
  selector:
    matchLabels:
      app: khmermart24
  template:
    metadata:
      labels:
        app: khmermart24
    spec:
      containers:
      - name: khmermart24
        image: khmermart24:v1.0.0
        ports:
        - containerPort: 80
        envFrom:
        - configMapRef:
            name: khmermart24-config
        livenessProbe:
          httpGet:
            path: /health
            port: 80
          initialDelaySeconds: 30
          periodSeconds: 10
```

#### 4. VPS/Server Deployment
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

#### 5. Platform-as-a-Service (Render/Fly.io/Heroku)
The app is configured for PaaS deployment with:
- Dockerfile for container-based deployment
- Automatic build optimization
- Environment variable configuration
- Health check endpoints at `/health`

### Docker Services

The Docker setup includes:

#### Development Services
- **App Container**: Laravel application with PHP 8.2 + Nginx
- **MySQL 8.0**: Database server with persistent storage
- **Redis 7**: Caching and session storage
- **PHPMyAdmin**: Database management interface

#### Service URLs (Development)
- 🌐 **Application**: http://localhost:8000
- 🗄️ **PHPMyAdmin**: http://localhost:8080
- 🔴 **Redis**: localhost:6379
- 🐬 **MySQL**: localhost:3306

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

### Docker Deployment
- [ ] Docker and Docker Compose installed
- [ ] Environment variables configured in `.env`
- [ ] SSL certificates mounted (production)
- [ ] Health check returns 200 OK: `curl http://localhost/health`
- [ ] Admin endpoints protected (403 for non-admin)
- [ ] Database migrations applied
- [ ] Storage permissions set correctly
- [ ] Container logs clean and error-free

### Traditional Deployment
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
2. **Docker Multi-stage**: Optimized production images
3. **Nginx**: Static asset caching and gzip compression
4. **OPcache**: PHP bytecode optimization
5. **Database**: Proper indexing and relationships
6. **Sessions**: Redis-backed session storage

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
