import { useEffect, useState } from 'react';
import ProductGrid from './components/ProductGrid.demo';

const CART_KEY = 'km24:cart';

function getCart() {
  try { return JSON.parse(localStorage.getItem(CART_KEY)) || { items: [] }; }
  catch { return { items: [] }; }
}
function setCart(c) { localStorage.setItem(CART_KEY, JSON.stringify(c)); }

/**
 * Simple App component following Week 9 lecture demo pattern
 * This is a simplified version for teaching purposes
 */
export default function App() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [cart, setCartState] = useState(getCart());

  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        setLoading(true); setError('');
        const res = await fetch('/api/products'); // or '/products.json'
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        if (!cancelled) setProducts(data.data || data);
      } catch (e) {
        if (!cancelled) setError(e.message || 'Failed to load');
      } finally {
        if (!cancelled) setLoading(false);
      }
    })();
    return () => { cancelled = true; };
  }, []);

  function addToCart(product, qty = 1) {
    const next = { ...cart };
    const idx = next.items.findIndex(i => i.id === product.id);
    if (idx >= 0) next.items[idx].qty += qty;
    else next.items.push({ id: product.id, name: product.name, price: product.price, qty });
    setCart(next); setCartState(next);
  }

  return (
    <div className="max-w-6xl mx-auto p-4">
      <header className="flex items-center justify-between mb-4">
        <h1 className="text-2xl font-bold">KhmerMart24</h1>
        <span className="relative">
          🛒 <span className="ml-1 text-sm">{cart.items.reduce((n,i)=>n+i.qty,0)}</span>
        </span>
      </header>

      {loading && <p>Loading…</p>}
      {error && <p className="text-red-600" role="alert">{error}</p>}

      {!loading && !error && (
        <ProductGrid products={products} onAdd={addToCart} />
      )}
    </div>
  );
}
