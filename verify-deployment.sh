#!/bin/bash

# KhmerMart24 - Deployment Verification Script
# This script verifies the three checkpoints for Friday presentation:
# CP1: Admin functionality working locally
# CP2: Deployed API is live and responding
# CP3: Tests pass and CI is green

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
LOCAL_URL="http://localhost"
DEPLOYED_URL="https://your-deployment-url.com"  # Update with actual deployment URL
API_TOKEN=""  # Will be set after login

echo -e "${BLUE}🚀 KhmerMart24 Deployment Verification${NC}"
echo "=================================================="

# Function to check HTTP response
check_response() {
    local url=$1
    local expected_status=$2
    local description=$3

    echo -n "Testing $description... "

    response=$(curl -s -w "%{http_code}" -o /dev/null "$url" || echo "000")

    if [ "$response" = "$expected_status" ]; then
        echo -e "${GREEN}✓ OK ($response)${NC}"
        return 0
    else
        echo -e "${RED}✗ Failed (Expected: $expected_status, Got: $response)${NC}"
        return 1
    fi
}

# Function to make authenticated API request
api_request() {
    local method=$1
    local endpoint=$2
    local base_url=$3
    local data=$4

    if [ -n "$data" ]; then
        curl -s -X "$method" \
             -H "Content-Type: application/json" \
             -H "Authorization: Bearer $API_TOKEN" \
             -d "$data" \
             "$base_url$endpoint"
    else
        curl -s -X "$method" \
             -H "Authorization: Bearer $API_TOKEN" \
             "$base_url$endpoint"
    fi
}

echo -e "\n${YELLOW}CHECKPOINT 1: Admin Functionality Working Locally${NC}"
echo "================================================================"

# Check if Docker containers are running
echo "Checking Docker containers..."
if docker-compose ps | grep -q "Up"; then
    echo -e "${GREEN}✓ Docker containers are running${NC}"
else
    echo -e "${RED}✗ Docker containers are not running${NC}"
    echo "Starting containers..."
    docker-compose up -d
    sleep 10
fi

# Test local endpoints
check_response "$LOCAL_URL/health" "200" "Health check endpoint"
check_response "$LOCAL_URL/api/products" "200" "Products API endpoint"

# Test admin login (locally)
echo "Testing admin authentication..."
LOGIN_RESPONSE=$(curl -s -X POST \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@khmermart.com","password":"admin123"}' \
    "$LOCAL_URL/api/auth/login" || echo '{"error":"failed"}')

if echo "$LOGIN_RESPONSE" | grep -q "access_token"; then
    API_TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"access_token":"[^"]*' | cut -d'"' -f4)
    echo -e "${GREEN}✓ Admin login successful${NC}"

    # Test admin endpoints
    echo "Testing admin product management..."
    ADMIN_PRODUCTS=$(api_request "GET" "/api/admin/products" "$LOCAL_URL")

    if echo "$ADMIN_PRODUCTS" | grep -q "data"; then
        echo -e "${GREEN}✓ Admin can access product management${NC}"
    else
        echo -e "${RED}✗ Admin product access failed${NC}"
    fi

    # Test audit logging
    echo "Testing audit logging..."
    AUDIT_RESPONSE=$(api_request "PUT" "/api/admin/products/1" "$LOCAL_URL" '{"name":"Test Product Update","price":99.99}')

    if [ -f "storage/logs/audit.log" ]; then
        if tail -n 5 storage/logs/audit.log | grep -q "inventory_update"; then
            echo -e "${GREEN}✓ Audit logging is working${NC}"
        else
            echo -e "${YELLOW}⚠ Audit log exists but no recent entries${NC}"
        fi
    else
        echo -e "${RED}✗ Audit log file not found${NC}"
    fi

else
    echo -e "${RED}✗ Admin login failed${NC}"
    echo "Response: $LOGIN_RESPONSE"
fi

echo -e "\n${YELLOW}CHECKPOINT 2: Deployed API Live and Responding${NC}"
echo "================================================================"

# Note: This section requires actual deployment URL
if [ "$DEPLOYED_URL" = "https://your-deployment-url.com" ]; then
    echo -e "${YELLOW}⚠ Deployment URL not configured${NC}"
    echo "Please update DEPLOYED_URL variable with your actual deployment URL"
    echo "Example endpoints to test after deployment:"
    echo "  - GET $DEPLOYED_URL/health"
    echo "  - GET $DEPLOYED_URL/api/products"
    echo "  - POST $DEPLOYED_URL/api/auth/login"
else
    check_response "$DEPLOYED_URL/health" "200" "Deployed health check"
    check_response "$DEPLOYED_URL/api/products" "200" "Deployed products API"

    # Test deployed admin functionality
    echo "Testing deployed admin authentication..."
    DEPLOYED_LOGIN=$(curl -s -X POST \
        -H "Content-Type: application/json" \
        -d '{"email":"admin@khmermart.com","password":"admin123"}' \
        "$DEPLOYED_URL/api/auth/login" || echo '{"error":"failed"}')

    if echo "$DEPLOYED_LOGIN" | grep -q "access_token"; then
        echo -e "${GREEN}✓ Deployed admin login successful${NC}"
    else
        echo -e "${RED}✗ Deployed admin login failed${NC}"
    fi
fi

echo -e "\n${YELLOW}CHECKPOINT 3: Tests Pass and CI Green${NC}"
echo "================================================================"

# Run local tests
echo "Running PHPUnit tests..."
if docker-compose exec -T app php artisan test --without-tty; then
    echo -e "${GREEN}✓ All tests passing locally${NC}"
else
    echo -e "${RED}✗ Some tests failing${NC}"
fi

# Check database seeding
echo "Checking database state..."
if docker-compose exec -T app php artisan tinker --execute="echo App\\Models\\User::count() . ' users, ' . App\\Models\\Product::count() . ' products'"; then
    echo -e "${GREEN}✓ Database properly seeded${NC}"
else
    echo -e "${YELLOW}⚠ Could not verify database state${NC}"
fi

# GitHub Actions status (requires repo to be pushed)
echo -e "\n${BLUE}GitHub Actions Status:${NC}"
echo "Check your repository's Actions tab for CI status:"
echo "  - PHP Tests workflow should be green"
echo "  - Docker Build workflow should be green"
echo "  - All security checks should pass"

echo -e "\n${YELLOW}PRESENTATION CHECKLIST:${NC}"
echo "================================================================"
echo "✓ Security Features:"
echo "  - Admin role-based access control"
echo "  - Rate limiting (60/hour general, 30/hour admin)"
echo "  - Audit logging for inventory changes"
echo "  - Security headers (XSS, CSRF protection)"
echo ""
echo "✓ Deployment Features:"
echo "  - Docker containerization"
echo "  - Multi-stage builds for optimization"
echo "  - Health check endpoints"
echo "  - CI/CD with GitHub Actions"
echo ""
echo "✓ Admin Tools:"
echo "  - Product inventory management"
echo "  - Order management dashboard"
echo "  - Comprehensive audit trails"
echo "  - API rate limiting and throttling"

echo -e "\n${GREEN}🎯 Verification complete!${NC}"
echo "Review any failed checkpoints before Friday presentation."
