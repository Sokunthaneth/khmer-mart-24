# Week 8 — JavaScript for Interactivity Implementation

This document describes the JavaScript functionality added to KhmerMart24 in Week 8 to make the static pages interactive.

## 🚀 Features Implemented

### ✅ Product Loading & Display
- **Async Product Fetching**: Products are loaded dynamically from Laravel API (`/api/products`) with fallback to static JSON
- **Featured Products**: Home page displays 6 featured products
- **Product Grid**: Products page shows all products with pagination support
- **Error Handling**: Graceful fallbacks when API is unavailable

### ✅ Shopping Cart Functionality
- **Add to Cart**: Click "Add to Cart" buttons to add items
- **Cart Persistence**: Cart state saved in localStorage (survives page reloads)
- **Cart Badge**: Navigation shows real-time item count
- **Remove Items**: Remove individual items from cart
- **Quantity Control**: Adjust item quantities with +/- buttons
- **Cart Page**: Dedicated page to review and manage cart items

### ✅ Interactive UI Elements
- **Event Delegation**: Efficient event handling for dynamic content
- **Loading States**: Spinners and loading messages during async operations
- **Success/Error Messages**: User feedback for all actions
- **Real-time Updates**: Cart badge and totals update instantly

### ✅ Search & Filtering
- **Product Search**: Live search with 300ms debounce
- **Category Filter**: Filter products by category
- **Dynamic Categories**: Categories loaded from API/JSON

## 📁 File Structure

```
frontend/
├── js/
│   ├── api.js              # API communication & fallbacks
│   ├── cart.js             # Cart state management
│   ├── utils.js            # UI utilities (loading, errors, formatting)
│   ├── products.js         # Product rendering & interactions
│   ├── home.js             # Home page initialization
│   ├── products-page.js    # Products page initialization
│   └── cart-page.js        # Cart page functionality
├── index.html              # Home page (updated)
├── products.html           # Products page (updated)
├── cart.html               # New cart page
├── products.json           # Fallback product data
└── categories.json         # Fallback category data
```

## 🎮 How to Test

### Option 1: With Laravel Backend
1. Start the Laravel development server:
   ```bash
   php artisan serve
   ```
2. Navigate to `http://localhost:8000/frontend/`
3. JavaScript will load products from Laravel API

### Option 2: Static Files (for testing)
1. Serve the frontend folder with any HTTP server:
   ```bash
   # Using Python
   cd frontend && python -m http.server 8080
   
   # Using Node.js
   cd frontend && npx serve .
   
   # Using PHP
   cd frontend && php -S localhost:8080
   ```
2. Navigate to `http://localhost:8080`
3. JavaScript will use fallback JSON files

### Test Scenarios

1. **Product Loading**:
   - Visit home page → Should load 6 featured products
   - Visit products page → Should load all products with search/filter

2. **Cart Functionality**:
   - Click "Add to Cart" on any product
   - Check cart badge updates in navigation
   - Visit cart page to see added items
   - Adjust quantities and remove items

3. **Search & Filter**:
   - Use search box on products page
   - Select different categories from dropdown

4. **Error Handling**:
   - Disable network → Should show fallback JSON data
   - Block JSON files → Should show error messages

## 🏗️ Architecture Highlights

### Module System
- Uses ES6 modules (`type="module"`)
- Clear separation of concerns
- Reusable utility functions

### State Management
- Cart state in localStorage as single source of truth
- Custom events for state updates (`cartUpdated`)
- Reactive UI updates

### Event Handling
- Event delegation for dynamic content
- Debounced search input
- Proper error boundaries

### API Design
- Graceful API fallbacks
- Consistent error handling
- Loading state management

## 🎯 Learning Objectives Met

✅ **DOM Manipulation**: Dynamic product rendering, cart updates  
✅ **Event Handling**: Delegation, debouncing, form interactions  
✅ **Async JavaScript**: Fetch API, error handling, loading states  
✅ **Local Storage**: Cart persistence, state management  
✅ **User Experience**: Loading indicators, success/error feedback  

## 🔧 Technical Implementation Details

### Cart State Structure
```javascript
{
  items: [
    {
      id: 1,
      name: "Product Name",
      price: 99.99,
      image: "image_url",
      qty: 2
    }
  ]
}
```

### Event Delegation Pattern
```javascript
container.addEventListener('click', (e) => {
  const button = e.target.closest('[data-add-to-cart]');
  if (!button) return;
  // Handle add to cart
});
```

### API Fallback Strategy
```javascript
try {
  return await apiRequest('/api/products');
} catch (error) {
  const response = await fetch('./products.json');
  return await response.json();
}
```

## 🎨 Styling Notes

- Uses existing Tailwind CSS classes
- Loading spinners with CSS animations
- Success/error messages with proper colors
- Responsive design maintained

## 🚧 Future Enhancements

- Product sorting functionality
- Infinite scroll for products
- Cart item images optimization
- Search highlighting
- Product quick view modal
- Checkout process implementation

## 📋 Friday Presentation Checklist

- [ ] Demo product loading from API
- [ ] Show add to cart functionality
- [ ] Demonstrate cart persistence (reload page)
- [ ] Show search and filter features
- [ ] Display error handling (network disabled)
- [ ] Walk through JavaScript code structure
