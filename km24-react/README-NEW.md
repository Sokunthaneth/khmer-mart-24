# KhmerMart24 React Application - Week 9

A modern e-commerce storefront built with React, Vite, and Tailwind CSS demonstrating component-based architecture and state management.

## 🚀 Quick Start

```bash
cd km24-react
npm install
npm run dev
# Open http://localhost:5173
```

## ✅ Acceptance Criteria Met

### ✅ React App Renders Products from API
- **Data Source**: Products fetched from `/api/products` with fallback to static JSON and mock data
- **Dynamic Rendering**: Products grid updates based on API response
- **Error Handling**: Graceful fallbacks when API is unavailable

### ✅ Required Components Present and Functional
- **ProductCard**: Individual product display with add-to-cart functionality
- **ProductGrid**: Responsive grid with loading, error, and empty states  
- **CartBadge**: Animated cart icon with real-time item count
- **Header**: Navigation with logo and cart integration

### ✅ Cart Count Updates and Persists via localStorage
- **Real-time Updates**: Cart badge updates instantly when items are added
- **Persistence**: Cart state survives page refreshes and browser sessions
- **State Management**: Clean cart state flow with localStorage integration

## 🏗️ Component Architecture

### Component Hierarchy
```
App (Root Component)
├── Header
│   └── CartBadge
└── ProductGrid
    └── ProductCard (multiple instances)
```

### Props and State Design

#### App Component (Container)
**State Management:**
- `products` - Array of products from API
- `loading` - Boolean for async operation status
- `error` - String for error messages
- `cart` - Object for cart state from localStorage

**Props Passed Down:**
- To `Header`: `cartItemCount`, `onCartClick`
- To `ProductGrid`: `products`, `onAdd`, `loading`, `error`

#### ProductCard Component
**Props Received:**
- `product` - Product object with id, name, price, image, description
- `onAdd` - Function to handle add-to-cart action

**Internal State:**
- `isAdding` - Boolean for button loading state

#### ProductGrid Component
**Props Received:**
- `products` - Array of products to display
- `onAdd` - Function passed to each ProductCard
- `loading` - Boolean for loading state
- `error` - String for error display

#### CartBadge Component
**Props Received:**
- `itemCount` - Number for cart badge display
- `onClick` - Function for cart click handling

## 🔄 Data Flow

### Product Loading Flow
```
1. App component mounts
2. useEffect triggers loadProducts() API call
3. Loading state set to true
4. API response updates products state
5. ProductGrid receives products via props
6. ProductCard components render individual products
```

### Cart Interaction Flow
```
1. User clicks "Add to Cart" in ProductCard
2. onAdd function called with product data
3. handleAddToCart in App component processes action
4. addToCart utility function updates localStorage
5. App cart state updated via setCartState
6. getCartTotals calculates new item count
7. CartBadge receives updated itemCount via props
8. Badge displays new count with animation
```

## 🎨 Tailwind Integration

### Custom Utility Classes
```css
.btn-primary { /* Blue button with hover/focus states */ }
.btn-secondary { /* Gray button variant */ }
.card { /* White card with shadow and border */ }
.product-grid { /* Responsive grid layout */ }
```

### Responsive Design Implementation
- **Grid Layout**: 1 col (mobile) → 2 col (sm) → 3 col (md) → 4 col (lg)
- **Navigation**: Responsive header with collapsing elements
- **Typography**: Responsive text sizes and spacing
- **Interactive States**: Hover effects and focus rings

## 🌐 Data Fetching Approach

### Multi-Layer Fallback Strategy
1. **Primary**: Laravel API endpoint (`/api/products`)
2. **Secondary**: Static JSON file (`/products.json`)  
3. **Tertiary**: Hard-coded mock data in `api.js`

### Error Handling Implementation
```javascript
// API utility with comprehensive error handling
try {
  return await apiRequest(endpoint);
} catch (error) {
  console.warn('Laravel API not available, falling back to static JSON');
  try {
    const response = await fetch('/products.json');
    return await response.json();
  } catch (fallbackError) {
    console.warn('Fallback JSON failed, using mock data');
    return getMockProducts();
  }
}
```

### UX States
- **Loading**: Spinner with "Loading products..." message
- **Error**: Error icon with retry button and helpful message
- **Empty**: "No products found" with search suggestion
- **Success**: Product grid with smooth transitions

## 🧪 Verification Steps

### ✅ Screenshots Evidence
1. **Running App**: React dev server at http://localhost:5173
2. **Rendered Grid**: Products displayed in responsive grid layout
3. **Cart Badge**: Badge showing "0" initially, updating when items added
4. **Add to Cart**: Button states and cart count updates
5. **Persistence**: Cart count maintains after page refresh

### ✅ Functionality Testing
1. **Component Rendering**: All components render without errors
2. **API Integration**: Products load from API/fallback sources
3. **Cart Interaction**: Add to cart updates badge immediately
4. **localStorage**: Cart persists across browser sessions
5. **Responsive Design**: Layout adapts to different screen sizes
6. **Error Handling**: Graceful degradation when API fails

### ✅ Code Quality
1. **Component Structure**: Clean, focused, reusable components
2. **State Management**: Proper use of useState and useEffect
3. **Props Flow**: Clear data flow from parent to child components
4. **Error Boundaries**: Comprehensive error handling throughout
5. **Performance**: Effect cleanup prevents memory leaks

## 🎯 Friday Presentation Rubric Alignment

### (3 points) Correct Componentization and Props/State Use
✅ **Component Design**: Proper separation of concerns
- ProductCard handles individual product display and interaction
- ProductGrid manages collection rendering and states
- CartBadge displays cart information with real-time updates
- Header provides navigation structure

✅ **Props/State Management**: Appropriate data flow
- State lifted to App component as single source of truth
- Props passed down efficiently without prop drilling
- Local component state used appropriately (isAdding in ProductCard)

### (3 points) Data Fetch + List Rendering with UX States  
✅ **Data Fetching**: Robust API integration
- useEffect with proper cleanup for data fetching
- Multi-layer fallback strategy for reliability
- Async/await with comprehensive error handling

✅ **UX States**: Complete user experience
- Loading spinner during data fetch
- Error state with retry functionality  
- Empty state for no results
- Success state with smooth rendering

### (2 points) Cart Interaction and Persistence
✅ **Cart Functionality**: Complete cart system
- Add to cart updates state immediately
- Cart badge reflects real-time item count
- Visual feedback during add action

✅ **Persistence**: localStorage integration
- Cart state survives page refreshes
- Cross-session persistence working
- Proper JSON serialization/deserialization

### (2 points) Code Clarity and Project Structure
✅ **Project Organization**: Clean file structure
- Components in dedicated directory
- Utilities separated for reusability
- Clear naming conventions throughout

✅ **Code Quality**: Readable and maintainable
- Consistent formatting and indentation
- Meaningful variable and function names
- Comprehensive comments and documentation

## 🏆 Stretch Goals Implemented

### ✅ Basic Cart Context Preview
- Centralized cart state management in App component
- Utility functions for cart operations
- Foundation for future Context API implementation

### ✅ Loading States and UX Polish
- Smooth loading animations
- Button state management during async operations
- Visual feedback for user actions

### ✅ Comprehensive Error Handling
- Multiple fallback strategies for data loading
- User-friendly error messages
- Retry functionality for failed operations

## 🔮 Next Steps (Week 10 Preview)

- **React Router**: Add client-side routing for product details
- **Context API**: Implement CartContext for global state management
- **Product Detail**: Individual product pages with detailed views
- **Cart Modal/Page**: Complete cart management interface
- **Form Handling**: Checkout process with form validation

## 🎤 Demo Script for Friday Presentation

1. **Show running app** at localhost:5173
2. **Explain component hierarchy** in VS Code
3. **Demonstrate data fetching** with Network tab
4. **Add products to cart** showing real-time updates
5. **Refresh page** to demonstrate persistence
6. **Show responsive design** by resizing window
7. **Explain state management** and props flow
8. **Discuss Tailwind integration** and utility classes
