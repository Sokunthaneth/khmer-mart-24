# Deployment Guide - KhmerMart24 React App 🚀

## Quick Deploy to Vercel (Recommended)

### 1. Install Vercel CLI
```bash
npm i -g vercel
```

### 2. Deploy
```bash
# From the km24-react directory
vercel --prod
```

### 3. Configure Environment Variables
In Vercel dashboard:
- Go to your project settings
- Add environment variable: `VITE_API_URL=https://your-backend-api.com`

## Alternative: Deploy to Netlify

### 1. Build the Project
```bash
npm run build
```

### 2. Deploy dist/ folder to Netlify
- Drag and drop the `dist/` folder to Netlify
- Or connect GitHub repository for automatic deployments

### 3. Configure SPA Routing
Create `public/_redirects` file:
```
/*    /index.html   200
```

## Environment Variables for Production

Create `.env.production`:
```bash
VITE_API_URL=https://your-production-api.com
VITE_APP_NAME=KhmerMart24
```

## Build Optimization

The build is already optimized with:
- ✅ Tree shaking for smaller bundle size
- ✅ CSS optimization with Tailwind purging
- ✅ Asset optimization and compression
- ✅ Code splitting for better performance

## SPA Configuration

For proper routing on static hosts, ensure:
- All routes redirect to `index.html`
- 404 errors serve `index.html` 
- History API fallback enabled

## Backend Integration

Make sure your Laravel backend:
- Has CORS configured for your frontend domain
- Accepts requests from your production URL
- Has proper API authentication setup

## SSL & Security

Production checklist:
- ✅ HTTPS enforced
- ✅ Secure cookie settings
- ✅ Environment variables not committed
- ✅ API keys properly secured

## Performance Monitoring

Consider adding:
- Google Analytics for user tracking
- Performance monitoring tools
- Error tracking (Sentry, LogRocket)

## Troubleshooting

**Common Issues:**

1. **Routes return 404**
   - Configure SPA fallback routing
   - Check server configuration

2. **API calls fail**
   - Verify CORS settings
   - Check environment variables
   - Confirm API URL accessibility

3. **Build fails**
   - Check for TypeScript errors
   - Verify all imports are correct
   - Ensure dependencies are installed

## Demo URL

Once deployed, test these flows:
- Homepage → Add to cart → View cart
- Login → Checkout → Order placement
- Profile → Order history
- Responsive design on mobile
