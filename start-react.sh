#!/bin/bash

# KhmerMart24 React Setup Script
# Week 9 Implementation

echo "🚀 Setting up KhmerMart24 React Application..."

# Navigate to React project directory
cd km24-react

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Start development server
echo "🔥 Starting development server..."
echo "📱 Open http://localhost:5173 in your browser"
echo "🛑 Press Ctrl+C to stop the server"

npm run dev
