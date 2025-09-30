# 🧪 Week 10 Testing & Verification Checklist

## 🎯 Pre-Presentation Testing (Run This Before Friday!)

### ✅ **Core Functionality Tests**

#### 1. **Routing & Navigation** (3 points)
- [ ] **Homepage** (`/`) loads without errors
- [ ] **Cart page** (`/cart`) accessible via navigation and URL
- [ ] **Login page** (`/login`) loads correctly
- [ ] **Browser back/forward** buttons work correctly
- [ ] **URL changes** happen without page refresh
- [ ] **Mobile navigation** works on small screens
- [ ] **404 handling** - invalid routes redirect appropriately

**Test Commands:**
```bash
# Start dev server
npm run dev

# Visit each route manually:
# http://localhost:5173/
# http://localhost:5173/cart
# http://localhost:5173/login
# http://localhost:5173/checkout (should redirect to login)
# http://localhost:5173/profile (should redirect to login)
```

#### 2. **Authentication Flow** (3 points)
- [ ] **Login form** accepts demo credentials
- [ ] **Token storage** persists in localStorage
- [ ] **Protected routes** redirect to login when not authenticated
- [ ] **Return path** works after login (redirects to intended page)
- [ ] **Session persistence** survives browser refresh
- [ ] **Logout** clears token and redirects to homepage
- [ ] **Auto-expiry** handles expired tokens gracefully

**Demo Credentials:**
- Email: `admin@khmermart24.com`
- Password: `password`

**Test Flow:**
1. Try to access `/checkout` → Should redirect to `/login`
2. Login with demo credentials → Should redirect to `/checkout`
3. Refresh page → Should stay authenticated
4. Logout → Should clear session and redirect home

#### 3. **Orders API Integration** (3 points)
- [ ] **Add to cart** functionality works
- [ ] **Cart badge** updates with item count
- [ ] **Cart page** shows correct items and totals
- [ ] **Checkout page** displays order summary
- [ ] **Place order** makes API call with auth headers
- [ ] **Success state** shows order confirmation
- [ ] **Error handling** displays meaningful messages
- [ ] **Cart clearing** happens after successful order

**Test Flow:**
1. Add multiple products to cart
2. Navigate to cart, verify items and totals
3. Proceed to checkout (login if needed)
4. Place order and verify success message
5. Check that cart is cleared

#### 4. **Build & Deployment** (1 point)
- [ ] **Production build** completes without errors
- [ ] **Bundle size** is reasonable (< 300KB compressed)
- [ ] **Environment variables** configured correctly
- [ ] **Dev proxy** works for API calls
- [ ] **Static assets** load correctly

**Test Commands:**
```bash
# Test build
npm run build

# Check bundle size
ls -lh dist/assets/

# Test dev server with proxy
npm run dev
# Verify API calls go to correct backend
```

### 🚀 **Stretch Goals Verification**

#### ✅ **Profile Page with Order History**
- [ ] **Profile page** loads for authenticated users
- [ ] **User info** displays correctly from token
- [ ] **Order history** makes API call to fetch orders
- [ ] **Order details** show properly formatted data
- [ ] **Error handling** for failed order fetch
- [ ] **Empty state** displays when no orders exist

#### ✅ **Token Refresh Flow**
- [ ] **Token expiry detection** works correctly
- [ ] **Auto-refresh** attempts before API calls
- [ ] **Refresh failure** logs out user appropriately
- [ ] **Enhanced auth headers** include refresh logic

### 📱 **User Experience Tests**

#### **Responsive Design**
- [ ] **Mobile layout** works on phone screens (< 768px)
- [ ] **Tablet layout** works on medium screens
- [ ] **Desktop layout** works on large screens
- [ ] **Navigation menu** adapts to screen size
- [ ] **Cart functionality** works on all screen sizes

#### **Loading States**
- [ ] **Product loading** shows spinner and message
- [ ] **Login process** shows loading during submission
- [ ] **Order placement** displays processing state
- [ ] **Profile loading** shows appropriate feedback

#### **Error Handling**
- [ ] **Network errors** display user-friendly messages
- [ ] **Invalid credentials** show clear error message
- [ ] **API failures** provide actionable feedback
- [ ] **Validation errors** guide user to fix issues

### 🎬 **Demo Preparation Checklist**

#### **Environment Setup**
- [ ] **Clean browser** (clear localStorage/cookies)
- [ ] **Dev server running** on localhost:5173
- [ ] **Backend API** available (or fallback data ready)
- [ ] **Demo credentials** memorized or visible
- [ ] **Browser dev tools** ready for network tab

#### **Demo Data Preparation**
- [ ] **Products** loading correctly from API/fallback
- [ ] **Cart** starts empty for clean demo
- [ ] **Demo user** can login successfully
- [ ] **Order API** responds (success or meaningful error)

#### **Presentation Materials**
- [ ] **Script** reviewed and practiced
- [ ] **Key talking points** memorized
- [ ] **Technical highlights** ready to explain
- [ ] **Backup plans** for potential failures
- [ ] **Timer** ready for time management

### 🐛 **Common Issues & Solutions**

#### **Build Fails**
```bash
# Clear cache and reinstall
rm -rf node_modules package-lock.json
npm install
npm run build
```

#### **Routes Don't Work**
- Check main.jsx router configuration
- Verify all page components are imported correctly
- Ensure RequireAuth wrapper is properly implemented

#### **Authentication Issues**
- Verify .env.local has correct API URL
- Check browser localStorage for token
- Confirm login API endpoint is accessible

#### **API Calls Fail**
- Check network tab for CORS issues
- Verify environment variables are loaded
- Confirm proxy configuration in vite.config.js

### 📊 **Success Metrics**

#### **Functional Requirements** ✅
- All routes navigate correctly
- Authentication flow complete
- Orders API integration working
- Build process successful

#### **User Experience** ✅
- Smooth navigation without page refreshes
- Clear loading and error states
- Responsive design on all devices
- Intuitive user flow

#### **Technical Quality** ✅
- Clean, maintainable code structure
- Proper error handling throughout
- Environment-based configuration
- Production-ready build

## 🏆 **Final Confidence Check**

Before your presentation, you should be able to confidently say:

> ✅ "I can navigate through all routes smoothly"  
> ✅ "The authentication flow works perfectly"  
> ✅ "I can complete a full order placement"  
> ✅ "The app handles errors gracefully"  
> ✅ "Everything is responsive and professional"  

### **Emergency Backup Plan**
If anything fails during demo:
1. Have screenshots of working features ready
2. Be able to explain the code even if live demo fails
3. Show the build process and deployment readiness
4. Emphasize the learning journey and technical growth

---

## 🎉 **You're Ready!**

This checklist ensures your Week 10 KhmerMart24 application meets all requirements and demonstrates production-ready React development skills. Your presentation will showcase:

- **Modern React patterns** with hooks and functional components
- **Professional routing** with React Router
- **Secure authentication** with JWT tokens
- **Complete API integration** with proper error handling
- **Production deployment** readiness

**Go confidently into your Friday presentation! 🌟**
