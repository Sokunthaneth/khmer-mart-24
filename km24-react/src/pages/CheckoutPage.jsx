import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { getCart, clearCart, getCartTotals } from "../utils/cart";
import { authHeaders } from "../lib/auth";
import Header from "../components/Header";

export default function CheckoutPage() {
    const [cart, setCart] = useState({ items: [] });
    const [totals, setTotals] = useState({ total: 0, count: 0 });
    const [orderStatus, setOrderStatus] = useState("idle"); // idle, loading, success, error
    const [error, setError] = useState("");
    const [orderData, setOrderData] = useState(null);
    const navigate = useNavigate();

    useEffect(() => {
        const currentCart = getCart();
        const currentTotals = getCartTotals();
        setCart(currentCart);
        setTotals(currentTotals);

        // Redirect if cart is empty
        if (currentCart.items.length === 0) {
            navigate("/cart");
        }
    }, [navigate]);

    const placeOrder = async () => {
        try {
            setOrderStatus("loading");
            setError("");

            // Prepare order data
            const orderPayload = {
                items: cart.items.map((item) => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price,
                })),
                total: totals.total,
            };

            const response = await fetch(
                `${import.meta.env.VITE_API_URL || ""}/api/orders`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        ...authHeaders(),
                    },
                    body: JSON.stringify(orderPayload),
                }
            );

            if (!response.ok) {
                throw new Error(
                    `HTTP ${response.status}: ${response.statusText}`
                );
            }

            const result = await response.json();
            setOrderData(result);
            setOrderStatus("success");

            // Clear cart on successful order
            clearCart();
        } catch (err) {
            console.error("Order submission failed:", err);
            setError(err.message || "Failed to place order. Please try again.");
            setOrderStatus("error");
        }
    };

    // Success state
    if (orderStatus === "success") {
        return (
            <>
                <Header />
                <div className="container mx-auto px-4 py-8">
                    <div className="max-w-md mx-auto text-center">
                        <div className="text-6xl mb-4">🎉</div>
                        <h1 className="text-2xl font-semibold text-gray-900 mb-2">
                            Order Placed Successfully!
                        </h1>
                        <p className="text-gray-600 mb-6">
                            Thank you for your order. You will receive a
                            confirmation email shortly.
                        </p>
                        {orderData?.order_number && (
                            <div className="bg-gray-50 p-4 rounded-lg mb-6">
                                <p className="text-sm text-gray-600">
                                    Order Number
                                </p>
                                <p className="text-lg font-mono font-semibold">
                                    {orderData.order_number}
                                </p>
                            </div>
                        )}
                        <div className="space-y-3">
                            <button
                                onClick={() => navigate("/profile")}
                                className="btn-primary w-full"
                            >
                                View Order History
                            </button>
                            <button
                                onClick={() => navigate("/")}
                                className="btn-secondary w-full"
                            >
                                Continue Shopping
                            </button>
                        </div>
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
                    Checkout
                </h1>

                <div className="lg:grid lg:grid-cols-12 lg:gap-8">
                    {/* Order Summary */}
                    <div className="lg:col-span-8">
                        <div className="card p-6 mb-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">
                                Order Summary
                            </h2>

                            <div className="space-y-4">
                                {cart.items.map((item) => (
                                    <div
                                        key={item.id}
                                        className="flex items-center space-x-4 py-3 border-b border-gray-200 last:border-b-0"
                                    >
                                        <div className="flex-shrink-0 w-16 h-16">
                                            <img
                                                src={
                                                    item.image ||
                                                    "/api/placeholder/64/64"
                                                }
                                                alt={item.name}
                                                className="w-full h-full object-cover rounded-lg"
                                            />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h3 className="font-medium text-gray-900 truncate">
                                                {item.name}
                                            </h3>
                                            <p className="text-sm text-gray-500">
                                                Qty: {item.quantity}
                                            </p>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-semibold text-gray-900">
                                                $
                                                {(
                                                    item.price * item.quantity
                                                ).toFixed(2)}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-6 pt-4 border-t border-gray-200">
                                <div className="flex justify-between text-lg font-semibold">
                                    <span>Total</span>
                                    <span>${totals.total.toFixed(2)}</span>
                                </div>
                            </div>
                        </div>

                        {/* Payment Info */}
                        <div className="card p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">
                                Payment Information
                            </h2>
                            <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p className="text-blue-700 text-sm">
                                    💡 <strong>Demo Mode:</strong> This is a
                                    demonstration checkout. No actual payment
                                    will be processed.
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Place Order */}
                    <div className="lg:col-span-4 mt-8 lg:mt-0">
                        <div className="card p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">
                                Place Order
                            </h2>

                            {error && (
                                <div
                                    className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4"
                                    role="alert"
                                >
                                    {error}
                                </div>
                            )}

                            <div className="space-y-3 mb-6">
                                <div className="flex justify-between text-sm">
                                    <span>Subtotal</span>
                                    <span>${totals.total.toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span>Tax</span>
                                    <span>$0.00</span>
                                </div>
                                <div className="border-t pt-3">
                                    <div className="flex justify-between font-semibold text-lg">
                                        <span>Total</span>
                                        <span>${totals.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>

                            <button
                                onClick={placeOrder}
                                disabled={
                                    orderStatus === "loading" ||
                                    cart.items.length === 0
                                }
                                className="btn-primary w-full mb-3 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {orderStatus === "loading"
                                    ? "Processing..."
                                    : "Place Order"}
                            </button>

                            <button
                                onClick={() => navigate("/cart")}
                                className="btn-secondary w-full"
                                disabled={orderStatus === "loading"}
                            >
                                Back to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
