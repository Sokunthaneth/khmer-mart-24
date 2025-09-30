# Week 9 — React Components Implementation

This document describes the React components implementation of KhmerMart24 for Week 9, converting the vanilla JavaScript storefront into a modern React application.

## 🚀 Project Overview

The React implementation rebuilds the KhmerMart24 storefront using:
- **React 19.1.1** with functional components and hooks
- **Vite 7.1.7** for fast development and building
- **Tailwind CSS 3.4.0** for styling and responsive design
- **Component-based architecture** for reusability and maintainability

## 📁 Project Structure

```
km24-react/
├── public/
│   ├── products.json           # Fallback product data
│   └── vite.svg
├── src/
│   ├── components/
│   │   ├── Header.jsx          # Navigation with logo and cart
│   │   ├── ProductCard.jsx     # Individual product display
│   │   ├── ProductGrid.jsx     # Product grid with loading/error states
│   │   └── CartBadge.jsx       # Cart icon with item count
│   ├── utils/
│   │   ├── api.js              # API calls with fallback to mock data
│   │   └── cart.js             # Cart localStorage management
│   ├── App.jsx                 # Main application component
│   ├── main.jsx               # React app entry point
│   └── index.css              # Tailwind CSS and custom styles
├── package.json
├── vite.config.js
├── tailwind.config.js
└── postcss.config.js
```

## 🧩 Components Architecture

### App.jsx (Main Container)
- **State Management**: Products, cart, loading, error states
- **Lifecycle**: useEffect for data fetching on mount
- **Props Flow**: Passes data and handlers to child components

### ProductGrid.jsx
- **Props**: `products`, `onAdd`, `loading`, `error`
- **Features**: 
  - Loading spinner
  - Error state with retry
  - Empty state
  - Responsive grid layout

### ProductCard.jsx
- **Props**: `product`, `onAdd`
- **Features**:
  - Product image with hover effects
  - Add to cart with loading state
  - Price formatting
  - Responsive design

### Header.jsx
- **Props**: `cartItemCount`, `onCartClick`
- **Features**:
  - Logo and navigation
  - Cart badge with item count
  - Responsive design

### CartBadge.jsx
- **Props**: `itemCount`, `onClick`
- **Features**:
  - Shopping cart icon
  - Animated badge with count
  - Accessibility support

## 🎣 Hooks Implementation

### useState Usage
```javascript
const [products, setProducts] = useState([]);
const [loading, setLoading] = useState(false);
const [error, setError] = useState('');
const [cart, setCartState] = useState(getCart());
```

### useEffect with Cleanup
```javascript
useEffect(() => {
  let cancelled = false;
  
  const fetchProducts = async () => {
    try {
      setLoading(true);
      const response = await loadProducts();
      if (!cancelled) setProducts(response.data || response);
    } catch (err) {
      if (!cancelled) setError(err.message);
    } finally {
      if (!cancelled) setLoading(false);
    }
  };

  fetchProducts();
  return () => { cancelled = true; };
}, []);
```

## 🔄 State Management

### Cart State Flow
1. **Initial Load**: Cart loaded from localStorage
2. **Add Item**: Product added via `handleAddToCart`
3. **State Update**: Cart state updated in component
4. **Persistence**: Cart saved to localStorage
5. **UI Update**: Badge count updates automatically

### Data Flow Pattern
```
API/Mock Data → App State → Component Props → UI Rendering
Cart Actions → Utility Functions → localStorage → State Update
```

## 🌐 API Integration

### Multi-Layer Fallback Strategy
1. **Primary**: Laravel API (`/api/products`)
2. **Secondary**: Static JSON (`/products.json`)
3. **Tertiary**: Hard-coded mock data in `api.js`

### Error Handling
- Network errors gracefully handled
- User-friendly error messages
- Retry functionality included

## 🎨 Tailwind CSS Integration

### Custom CSS Classes
```css
.btn-primary { /* Blue button with hover/focus states */ }
.btn-secondary { /* Gray button variant */ }
.card { /* White card with shadow and border */ }
.product-grid { /* Responsive grid layout */ }
```

### Responsive Design
- Mobile-first approach
- Grid: 1 col (mobile) → 2 col (sm) → 3 col (md) → 4 col (lg)
- Navigation collapses on mobile

## 🧪 Testing the Application

### Starting the Development Server
```bash
cd km24-react
npm run dev
# Open http://localhost:5173
```

### Test Scenarios

1. **Component Rendering**:
   - Products load and display in grid
   - Loading spinner shows during fetch
   - Error state displays if API fails

2. **Cart Functionality**:
   - Click "Add to Cart" buttons
   - Check cart badge updates
   - Verify localStorage persistence (refresh page)

3. **Responsive Design**:
   - Test different screen sizes
   - Verify grid layout changes
   - Check navigation responsiveness

4. **Error Handling**:
   - Disconnect network → Should show fallback data
   - Block JSON files → Should show mock data
   - API errors → Should show error state

### Browser Console Features
- Cart state logging on add
- API fallback warnings
- Error messages for debugging

## 🏗️ Implementation Highlights

### React Best Practices
- ✅ Functional components with hooks
- ✅ Props destructuring
- ✅ Key props for list items
- ✅ Event handler naming (handle*)
- ✅ Component composition
- ✅ Effect cleanup to prevent memory leaks

### Performance Considerations
- ✅ useEffect cleanup prevents state updates after unmount
- ✅ Loading states prevent multiple API calls
- ✅ Lazy loading for product images
- ✅ Efficient re-renders with proper dependencies

### Accessibility Features
- ✅ Semantic HTML elements
- ✅ Alt text for images
- ✅ ARIA labels for cart button
- ✅ Focus management
- ✅ Screen reader support

## 🔄 Comparison with Week 8 (Vanilla JS)

| Feature | Week 8 (Vanilla JS) | Week 9 (React) |
|---------|---------------------|-----------------|
| State Management | Manual DOM updates | React state + hooks |
| Component Reuse | Copy/paste HTML | Reusable components |
| Event Handling | Event delegation | React event props |
| Data Fetching | Promise chains | async/await in useEffect |
| Error Handling | Manual DOM manipulation | Declarative error states |
| Code Organization | Multiple JS files | Component-based |

## 🎯 Learning Objectives Achieved

✅ **React Bootstrap**: Vite project with proper configuration  
✅ **Component Architecture**: Reusable, composable components  
✅ **Props & State**: Data flow and state management  
✅ **Hooks**: useState, useEffect with cleanup  
✅ **API Integration**: Fetch with error handling  
✅ **Styling**: Tailwind CSS with custom components  
✅ **localStorage**: Cart persistence  

## 🔮 Next Steps (Week 10)

- Add React Router for navigation
- Implement cart page/modal
- Add product detail pages
- Form handling for checkout
- Context API for global state
- Advanced hooks (useReducer, useContext)

## 🐛 Common Issues & Solutions

### Tailwind Not Working
```bash
# Ensure proper installation and config
npm install -D tailwindcss@^3.4.0 postcss autoprefixer
```

### useEffect Infinite Loop
```javascript
// ❌ Missing dependency array
useEffect(() => { fetchData(); });

// ✅ Empty dependency array for mount only
useEffect(() => { fetchData(); }, []);
```

### Cart Not Persisting
- Check localStorage in browser DevTools
- Verify cart.js functions are called
- Ensure JSON serialization works

## 📱 Demo Script for Friday Presentation

1. **Show component structure** in VS Code
2. **Demonstrate product loading** from API/fallback
3. **Add items to cart** and show badge update
4. **Refresh page** to show persistence
5. **Show responsive design** by resizing window
6. **Explain React concepts**: components, props, state, effects
7. **Compare with vanilla JS** implementation
8. **Discuss future enhancements** for Week 10
