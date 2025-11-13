# Week 9 Lecture Implementation Guide

This document shows how the KhmerMart24 React app implements the Week 9 lecture materials.

## ✅ Week 9 Objectives - All Completed

### 1. React Project with Vite ✓
- Located in `km24-react/` folder
- Initialized with `npm create vite@latest km24-react -- --template react`
- Running with `npm run dev`

### 2. Tailwind CSS Integration ✓
- Installed: `tailwindcss`, `postcss`, `autoprefixer`
- Configured in `tailwind.config.js` with proper content paths
- Directives added in `src/index.css`

### 3. Core Components Created ✓

#### `ProductCard.jsx`
- Displays individual product with image, name, price
- "Add to Cart" button with loading state
- Hover effects and responsive design

#### `ProductGrid.jsx`
- Renders grid of ProductCard components
- Loading spinner during data fetch
- Error state with retry button
- Empty state handling

#### `CartBadge.jsx`
- Shows cart icon with item count
- Animated badge for visual feedback
- Click handler for cart interaction

#### `Header.jsx`
- Navigation bar with logo and CartBadge
- Responsive layout

### 4. State Management with Hooks ✓

#### `useState`
```javascript
const [products, setProducts] = useState([]);
const [loading, setLoading] = useState(false);
const [error, setError] = useState('');
const [cart, setCartState] = useState(getCart());
```

#### `useEffect`
```javascript
useEffect(() => {
  let cancelled = false;
  // Async data fetching with cleanup
  return () => { cancelled = true; };
}, []);
```

### 5. API Integration ✓
- `src/utils/api.js` handles API calls
- Fetches from `/api/products` endpoint
- Falls back to `/products.json` mock data
- Error handling and loading states

### 6. Cart Functionality ✓
- `src/utils/cart.js` manages cart logic
- localStorage persistence with key `km24:cart`
- Functions: `getCart()`, `setCart()`, `addToCart()`, `getCartTotals()`

## 📁 Project Structure Matches Lecture

```
km24-react/
├── public/
│   └── products.json           # Mock data fallback
├── src/
│   ├── components/
│   │   ├── Header.jsx          # Nav with logo and cart
│   │   ├── ProductCard.jsx     # Individual product
│   │   ├── ProductGrid.jsx     # Product grid
│   │   └── CartBadge.jsx       # Cart icon with count
│   ├── utils/
│   │   ├── api.js              # API calls
│   │   └── cart.js             # Cart localStorage
│   ├── App.jsx                 # Main container
│   ├── main.jsx                # Entry point
│   └── index.css               # Tailwind + custom styles
├── package.json
├── vite.config.js
├── tailwind.config.js
└── postcss.config.js
```

## 🎯 Lecture Demo Patterns

### Demo-Style Files (Simplified for Teaching)
For students following the lecture demos step-by-step, simplified versions are provided:

- `App.demo.jsx` - Minimalist App following exact demo pattern
- `ProductCard.demo.jsx` - Basic card without advanced features
- `ProductGrid.demo.jsx` - Simple grid without loading/error UI

These demo files match the lecture code exactly and can be used as learning references.

### Production Files (Current Implementation)
The main implementation includes enhancements beyond the demos:

- Better error handling
- Loading skeletons
- More sophisticated cart logic
- Context API ready (CartContext.jsx exists)
- Responsive design improvements

## 🚀 Running the Project

```bash
cd km24-react
npm install
npm run dev
```

App runs at http://localhost:5173

## 📚 Week 9 Topics Covered

### ✅ Checkpoints Met
1. App runs with Tailwind classes applied ✓
2. Product list renders from static array ✓
3. Product list renders from API ✓

### Components & Props
- ProductCard receives `product` and `onAdd` props
- ProductGrid receives `products`, `onAdd`, `loading`, `error` props
- Props flow from App → Grid → Card

### State & Effects
- State management with `useState`
- Side effects with `useEffect`
- Cleanup functions prevent memory leaks

### Data Fetching
- Async/await inside useEffect
- Loading and error states
- Fallback to mock data

### Componentization
- Reusable ProductCard component
- Composable ProductGrid layout
- Modular CartBadge

### File Structure
- Components in `components/` folder
- Utilities in `utils/` folder
- Clear separation of concerns

## 🎓 Alignment with Lecture Agenda

| Time Block | Topic | Implementation |
|------------|-------|----------------|
| 0:00–0:25 | Setup Vite + Tailwind | ✅ Complete |
| 0:25–1:00 | Components & props | ✅ ProductCard, ProductGrid, CartBadge |
| 1:00–1:35 | State & effects; data fetch | ✅ useState, useEffect, API calls |
| 1:35–2:00 | Componentization | ✅ Grid/Card/Badge components |
| 2:00–2:20 | Error/loading UX; patterns | ✅ Loading spinners, error messages |
| 2:20–2:30 | Q&A and wrap-up | Ready for discussion |

## 🌟 Bonus Features (Beyond Week 9)

The implementation includes stretch goals:
- ✅ Loading skeletons
- ✅ Cart context prepared (CartContext.jsx)
- ✅ Advanced error boundaries
- ✅ Accessibility attributes (ARIA labels)

## 📝 Next Steps (Week 10 Preview)

- React Router for product detail pages
- Complete cart checkout flow
- API integration with authentication
- Advanced state management patterns

## 🔍 Verification

All Week 9 acceptance criteria met:
- ✅ React app renders products from API
- ✅ Components: ProductCard, ProductGrid, CartBadge functional
- ✅ Cart count updates and persists via localStorage
- ✅ Tailwind CSS properly configured and applied
- ✅ Loading and error states implemented

---

**Conclusion**: The KhmerMart24 React implementation fully reflects the Week 9 lecture materials with additional production-ready enhancements.
