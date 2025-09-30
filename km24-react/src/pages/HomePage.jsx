import { useEffect, useState } from "react";
import Header from "../components/Header";
import ProductGrid from "../components/ProductGrid";
import { loadProducts } from "../utils/api";
import { getCart, addToCart, getCartTotals } from "../utils/cart";

/**
 * HomePage - Main product listing page
 */
export default function HomePage() {
    // State management
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const [cart, setCartState] = useState(getCart());

    // Load products on component mount
    useEffect(() => {
        let cancelled = false;

        const fetchProducts = async () => {
            try {
                setLoading(true);
                setError("");

                const response = await loadProducts();
                const productsData = response.data || response;

                if (!cancelled) {
                    setProducts(productsData);
                }
            } catch (err) {
                if (!cancelled) {
                    setError(err.message || "Failed to load products");
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        };

        fetchProducts();

        // Cleanup function to prevent state updates if component unmounts
        return () => {
            cancelled = true;
        };
    }, []);

    // Handle adding items to cart
    const handleAddToCart = async (product, qty = 1) => {
        try {
            const updatedCart = addToCart(product, qty);
            setCartState(updatedCart);

            // Optional: Add visual feedback
            console.log(`Added ${product.name} to cart`);
        } catch (error) {
            console.error("Failed to add to cart:", error);
        }
    };

    // Calculate cart totals
    const cartTotals = getCartTotals(cart);

    return (
        <div className="min-h-screen bg-gray-50">
            <Header cartItemCount={cartTotals.count} />

            <main className="mx-auto max-w-7xl px-4 py-8">
                {/* Hero Section */}
                <section className="mb-12">
                    <div className="rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8 md:p-12 text-center">
                        <h1 className="text-3xl md:text-4xl font-bold mb-4">
                            Welcome to KhmerMart24
                        </h1>
                        <p className="text-lg md:text-xl opacity-90 mb-6 max-w-2xl mx-auto">
                            Discover amazing deals on quality products. Now with
                            full shopping cart and checkout flow!
                        </p>
                    </div>
                </section>

                {/* Products Section */}
                <section>
                    <div className="flex justify-between items-center mb-8">
                        <h2 className="text-2xl font-bold text-gray-900">
                            Featured Products
                        </h2>
                    </div>

                    <ProductGrid
                        products={products}
                        onAdd={handleAddToCart}
                        loading={loading}
                        error={error}
                    />
                </section>

                {/* Cart Summary (for debugging) */}
                {cartTotals.count > 0 && (
                    <section className="mt-12 bg-white rounded-lg p-6 shadow-sm border">
                        <h3 className="text-lg font-semibold mb-4">
                            Cart Summary
                        </h3>
                        <div className="flex justify-between items-center">
                            <span>Items: {cartTotals.count}</span>
                            <span className="font-bold">
                                Total: ${cartTotals.total.toFixed(2)}
                            </span>
                        </div>
                    </section>
                )}
            </main>

            {/* Footer */}
            <footer className="border-t bg-white mt-16">
                <div className="mx-auto max-w-7xl px-4 py-8 text-center">
                    <p className="text-gray-600">
                        &copy; 2025 KhmerMart24. Built with React, Vite, and
                        Tailwind CSS.
                    </p>
                </div>
            </footer>
        </div>
    );
}
