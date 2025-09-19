#!/bin/bash

# KhmerMart24 Docker Build Script
# Builds production-ready Docker image

set -e

# Configuration
IMAGE_NAME="khmermart24"
VERSION=${1:-latest}
REGISTRY=${REGISTRY:-""}

echo "🐳 Building KhmerMart24 Docker image..."
echo "📦 Image: ${IMAGE_NAME}:${VERSION}"

# Build production image
echo "🔨 Building production image..."
docker build \
    --target base \
    --tag ${IMAGE_NAME}:${VERSION} \
    --tag ${IMAGE_NAME}:latest \
    .

# Build development image
echo "🔨 Building development image..."
docker build \
    --target development \
    --tag ${IMAGE_NAME}:dev \
    .

# Display image info
echo "📊 Image information:"
docker images | grep ${IMAGE_NAME}

# If registry is provided, tag and push
if [ ! -z "$REGISTRY" ]; then
    echo "🚀 Pushing to registry: ${REGISTRY}"

    # Tag for registry
    docker tag ${IMAGE_NAME}:${VERSION} ${REGISTRY}/${IMAGE_NAME}:${VERSION}
    docker tag ${IMAGE_NAME}:latest ${REGISTRY}/${IMAGE_NAME}:latest

    # Push to registry
    docker push ${REGISTRY}/${IMAGE_NAME}:${VERSION}
    docker push ${REGISTRY}/${IMAGE_NAME}:latest

    echo "✅ Images pushed to registry"
fi

echo "🎉 Build completed successfully!"
echo ""
echo "🏃 Quick start commands:"
echo "  Development: docker-compose up -d"
echo "  Production:  docker run -p 80:80 --env-file .env ${IMAGE_NAME}:${VERSION}"
echo "  Health check: curl http://localhost/health"
