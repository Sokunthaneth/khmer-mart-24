import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import {
    getCart,
    removeFromCart,
    updateCartItemQuantity,
    getCartTotals,
} from "../utils/cart";
import Header from "../components/Header";

export default function CartPage() {
    const [cart, setCart] = useState({ items: [] });
    const [totals, setTotals] = useState({ total: 0, count: 0 });

    useEffect(() => {
        loadCart();
    }, []);

    const loadCart = () => {
        const currentCart = getCart();
        const currentTotals = getCartTotals();
        setCart(currentCart);
        setTotals(currentTotals);
    };

    const handleRemoveItem = (productId) => {
        removeFromCart(productId);
        loadCart();
    };

    const handleUpdateQuantity = (productId, quantity) => {
        if (quantity <= 0) {
            handleRemoveItem(productId);
        } else {
            updateCartItemQuantity(productId, quantity);
            loadCart();
        }
    };

    if (cart.items.length === 0) {
        return (
            <>
                <Header />
                <div className="container mx-auto px-4 py-8">
                    <div className="text-center py-16">
                        <div className="text-6xl mb-4">🛒</div>
                        <h1 className="text-2xl font-semibold text-gray-900 mb-2">
                            Your cart is empty
                        </h1>
                        <p className="text-gray-600 mb-6">
                            Looks like you haven't added anything to your cart
                            yet.
                        </p>
                        <Link to="/" className="btn-primary">
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </>
        );
    }

    return (
        <>
            <Header />
            <div className="container mx-auto px-4 py-8">
                <h1 className="text-2xl font-semibold text-gray-900 mb-6">
                    Shopping Cart
                </h1>

                <div className="lg:grid lg:grid-cols-12 lg:gap-8">
                    {/* Cart Items */}
                    <div className="lg:col-span-8">
                        <div className="space-y-4">
                            {cart.items.map((item) => (
                                <div key={item.id} className="card p-4">
                                    <div className="flex items-center space-x-4">
                                        {/* Product Image */}
                                        <div className="flex-shrink-0 w-20 h-20">
                                            <img
                                                src={
                                                    item.image ||
                                                    "/api/placeholder/80/80"
                                                }
                                                alt={item.name}
                                                className="w-full h-full object-cover rounded-lg"
                                            />
                                        </div>

                                        {/* Product Details */}
                                        <div className="flex-1 min-w-0">
                                            <h3 className="font-medium text-gray-900 truncate">
                                                {item.name}
                                            </h3>
                                            <p className="text-sm text-gray-500 truncate">
                                                {item.description}
                                            </p>
                                            <p className="text-lg font-semibold text-gray-900">
                                                ${item.price}
                                            </p>
                                        </div>

                                        {/* Quantity Controls */}
                                        <div className="flex items-center space-x-2">
                                            <button
                                                onClick={() =>
                                                    handleUpdateQuantity(
                                                        item.id,
                                                        item.quantity - 1
                                                    )
                                                }
                                                className="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50"
                                            >
                                                -
                                            </button>
                                            <span className="w-8 text-center">
                                                {item.quantity}
                                            </span>
                                            <button
                                                onClick={() =>
                                                    handleUpdateQuantity(
                                                        item.id,
                                                        item.quantity + 1
                                                    )
                                                }
                                                className="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50"
                                            >
                                                +
                                            </button>
                                        </div>

                                        {/* Remove Button */}
                                        <button
                                            onClick={() =>
                                                handleRemoveItem(item.id)
                                            }
                                            className="text-red-600 hover:text-red-800 p-2"
                                            title="Remove item"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Order Summary */}
                    <div className="lg:col-span-4 mt-8 lg:mt-0">
                        <div className="card p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">
                                Order Summary
                            </h2>

                            <div className="space-y-2 mb-4">
                                <div className="flex justify-between text-sm">
                                    <span>Items ({totals.count})</span>
                                    <span>
                                        ${totals.subtotal?.toFixed(2) || "0.00"}
                                    </span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div className="border-t pt-2">
                                    <div className="flex justify-between font-semibold">
                                        <span>Total</span>
                                        <span>${totals.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>

                            <Link
                                to="/checkout"
                                className="btn-primary w-full mb-3"
                            >
                                Proceed to Checkout
                            </Link>

                            <Link to="/" className="btn-secondary w-full">
                                Continue Shopping
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
