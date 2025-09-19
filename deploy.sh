#!/bin/bash

# KhmerMart24 Production Deployment Script
# Based on Week 6 demo requirements

echo "🚀 Starting KhmerMart24 production deployment..."

# Put app in maintenance mode
echo "📋 Enabling maintenance mode..."
php artisan down || true

echo "📦 Installing production dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "🔧 Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗃️ Running migrations..."
php artisan migrate --force

echo "🔗 Creating storage link..."
php artisan storage:link

echo "🔑 Generating application key if needed..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "📂 Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo "✅ Bringing app back online..."
php artisan up

echo "🎉 Deployment completed successfully!"
echo "ℹ️  Health check: curl -s /health"
