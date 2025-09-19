#!/bin/bash

# KhmerMart24 Docker Development Setup
# Sets up local development environment with Docker

set -e

echo "🐳 Setting up KhmerMart24 development environment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker Desktop and try again."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from template..."
    cp .env.example .env

    # Update database configuration for Docker
    sed -i.bak 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i.bak 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
    sed -i.bak 's/DB_DATABASE=laravel/DB_DATABASE=khmermart24/' .env
    sed -i.bak 's/DB_USERNAME=root/DB_USERNAME=khmermart24/' .env
    sed -i.bak 's/DB_PASSWORD=/DB_PASSWORD=secret/' .env

    # Update cache and session drivers
    echo "" >> .env
    echo "# Docker Redis configuration" >> .env
    echo "CACHE_DRIVER=redis" >> .env
    echo "SESSION_DRIVER=redis" >> .env
    echo "REDIS_HOST=redis" >> .env
    echo "REDIS_PORT=6379" >> .env

    rm .env.bak
    echo "✅ Environment file configured for Docker"
fi

# Build and start services
echo "🚀 Starting Docker services..."
docker-compose up -d --build

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 10

# Check if MySQL is ready
echo "🔍 Checking MySQL connection..."
until docker-compose exec mysql mysql -u khmermart24 -psecret -e "SELECT 1" > /dev/null 2>&1; do
    echo "   MySQL not ready, waiting..."
    sleep 5
done
echo "✅ MySQL is ready"

# Generate application key if needed
echo "🔑 Setting up application..."
docker-compose exec app php artisan key:generate --force

# Run migrations and seeders
echo "🗃️ Setting up database..."
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec app php artisan storage:link

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo ""
echo "🎉 Development environment is ready!"
echo ""
echo "📍 Services:"
echo "  🌐 Application: http://localhost:8000"
echo "  🗄️ PHPMyAdmin: http://localhost:8080"
echo "  🔴 Redis: localhost:6379"
echo "  🐬 MySQL: localhost:3306"
echo ""
echo "🔍 Health check: curl http://localhost:8000/health"
echo ""
echo "🛠️ Useful commands:"
echo "  📊 View logs: docker-compose logs -f"
echo "  🔄 Restart: docker-compose restart"
echo "  🛑 Stop: docker-compose down"
echo "  🧹 Clean up: docker-compose down -v"
