#!/bin/bash

# KhmerMart24 Frontend Development Setup
# Week 7 - HTML5 & Tailwind CSS Storefront

echo "🚀 Setting up KhmerMart24 Frontend Development Environment"
echo "========================================================="

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ npm is not installed. Please install Node.js and npm first."
    exit 1
fi

# Install dependencies
echo "📦 Installing dependencies..."
npm install

# Create output directory if it doesn't exist
mkdir -p public

# Build Tailwind CSS
echo "🎨 Building Tailwind CSS..."
npx tailwindcss -i ./frontend/input.css -o ./public/output.css --minify

echo "✅ Frontend setup complete!"
echo ""
echo "🎯 Week 7 Development Commands:"
echo "================================"
echo ""
echo "1. Watch CSS changes (development):"
echo "   npm run css:watch"
echo ""
echo "2. Build CSS for production:"
echo "   npm run css:build"
echo ""
echo "3. Start development server with auto-reload:"
echo "   npm run frontend:dev"
echo ""
echo "4. Open files in browser:"
echo "   open frontend/index.html"
echo "   open frontend/products.html"
echo "   open frontend/pages/product.html"
echo ""
echo "📁 Project Structure:"
echo "===================="
echo "frontend/"
echo "├── index.html          # Homepage with hero & featured products"
echo "├── products.html       # Product listing with responsive grid"
echo "├── pages/"
echo "│   └── product.html    # Individual product page"
echo "├── input.css           # Tailwind CSS source"
echo "└── assets/             # Images and other assets"
echo ""
echo "public/"
echo "└── output.css          # Compiled Tailwind CSS"
echo ""
echo "🎯 Week 7 Objectives Status:"
echo "============================="
echo "✅ Semantic HTML5 structure (header, main, footer)"
echo "✅ Tailwind CSS configuration and build pipeline"
echo "✅ Responsive product grid (2-4 columns)"
echo "✅ Mobile-first responsive design"
echo "✅ Accessibility features (ARIA, landmarks, alt text)"
echo "✅ Clean design system with reusable components"
echo ""
echo "🔍 Verification Checklist:"
echo "=========================="
echo "□ Header/nav/hero render correctly on mobile"
echo "□ Grid wraps responsively (sm/md/lg breakpoints)"
echo "□ Product page layout works on mobile and desktop"
echo "□ All images have proper alt text"
echo "□ Navigation is keyboard accessible"
echo "□ Color contrast meets accessibility standards"
echo ""
echo "Ready for Week 8 JavaScript interactivity! 🎉"
