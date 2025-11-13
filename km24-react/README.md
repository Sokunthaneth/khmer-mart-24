# KhmerMart24 React Frontend - Week 9 Implementation

React-based storefront for KhmerMart24 e-commerce platform, built with Vite and Tailwind CSS.

## 🚀 Quick Start

```bash
npm install
npm run dev
```

Visit http://localhost:5173

## 📚 Week 9 Documentation

- **[WEEK9_LECTURE_IMPLEMENTATION.md](./WEEK9_LECTURE_IMPLEMENTATION.md)** - Full implementation guide showing how the project meets all Week 9 objectives
- **[DEMO_CODE_REFERENCE.md](./DEMO_CODE_REFERENCE.md)** - Maps lecture demo code to actual implementation files

## ✅ Week 9 Objectives Met

All acceptance criteria complete:
- ✅ React app renders products fetched from API (with mock fallback)
- ✅ Components: `ProductCard`, `ProductGrid`, `CartBadge` present and functional
- ✅ Cart count updates and persists via `localStorage`
- ✅ Tailwind CSS integrated and working
- ✅ Loading and error states implemented

## 🧩 Project Structure

```
src/
├── components/
│   ├── Header.jsx              # Navigation with cart badge
│   ├── ProductCard.jsx         # Individual product display
│   ├── ProductGrid.jsx         # Product grid with states
│   ├── CartBadge.jsx           # Cart icon with count
│   ├── ProductCard.demo.jsx    # Simplified demo version
│   └── ProductGrid.demo.jsx    # Simplified demo version
├── utils/
│   ├── api.js                  # API calls with fallback
│   └── cart.js                 # Cart localStorage management
├── context/
│   └── CartContext.jsx         # Context API (stretch goal)
├── App.jsx                     # Main application
├── App.demo.jsx                # Simplified demo version
├── main.jsx                    # Entry point
└── index.css                   # Tailwind + custom styles
```

## 🎯 Core Features

### Components
- **ProductCard**: Displays product with add-to-cart functionality
- **ProductGrid**: Responsive grid with loading/error states
- **CartBadge**: Shows cart item count with visual feedback
- **Header**: Navigation bar with branding and cart

### State Management
- `useState` for products, cart, loading, error states
- `useEffect` for data fetching with cleanup
- localStorage persistence for cart

### API Integration
- Fetches from `/api/products` (Laravel backend)
- Falls back to `/products.json` mock data
- Error handling and retry logic

## 📖 Learning Resources

### Demo Files (For Students)
Follow lecture demos step-by-step using:
- `App.demo.jsx` - Simplified App matching lecture
- `ProductCard.demo.jsx` - Basic product card
- `ProductGrid.demo.jsx` - Simple grid layout

These files match the lecture code exactly for learning purposes.

### Production Files (Full Implementation)
Production-ready versions with enhancements:
- Better error handling
- Loading states and skeletons
- Accessibility attributes
- Responsive design

## 🛠️ Tech Stack

- **React 19.1.1** - UI library with hooks
- **Vite 7.1.7** - Fast build tool and dev server
- **Tailwind CSS 3.4.17** - Utility-first CSS framework
- **ESLint** - Code linting

## 📝 Available Scripts

- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run preview` - Preview production build
- `npm run lint` - Run ESLint

## 🔧 Configuration

### Vite
See `vite.config.js` for React plugin setup

### Tailwind
See `tailwind.config.js` for content paths and theme

### PostCSS
See `postcss.config.js` for Tailwind processing

## 🎓 Week 9 Topics Covered

1. ✅ **Vite Setup** - Fast development with HMR
2. ✅ **Tailwind Integration** - Utility-first styling
3. ✅ **Component Basics** - JSX, props, composition
4. ✅ **State & Effects** - useState, useEffect patterns
5. ✅ **Data Fetching** - Async/await with error handling
6. ✅ **Componentization** - Reusable ProductCard/Grid/Badge
7. ✅ **File Structure** - Clear separation of concerns

## 🌟 Bonus Features

- Loading skeletons for better UX
- Cart context prepared (CartContext.jsx)
- Advanced error boundaries
- ARIA labels for accessibility
- Responsive design throughout

## 🔍 Verification

To verify the implementation matches Week 9 requirements:

1. **Run the app**: `npm run dev`
2. **Check Tailwind**: Styles should be applied
3. **View products**: Grid should load from API or mock
4. **Test cart**: Add items, refresh page, count persists
5. **Review code**: Compare with lecture demos

All acceptance criteria verified! ✅

## 📚 Additional Resources

- [Vite Documentation](https://vite.dev/)
- [React Documentation](https://react.dev/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)

## 🔗 Related Documentation

- `../WEEK9_REACT_IMPLEMENTATION.md` - Project-level Week 9 docs
- `./WEEK9_LECTURE_IMPLEMENTATION.md` - Detailed implementation guide
- `./DEMO_CODE_REFERENCE.md` - Demo code mapping

---

**Note**: This project fully implements Week 9 lecture materials with additional production enhancements. Demo files (.demo.jsx) are provided for students following the lecture step-by-step.
