import { useState, useEffect } from "react";
import { getUserFromToken, logout, authHeaders } from "../lib/auth";
import Header from "../components/Header";

export default function ProfilePage() {
    const [user, setUser] = useState(null);
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        // Get user info from token
        const userInfo = getUserFromToken();
        setUser(userInfo);

        // Load user orders
        loadOrders();
    }, []);

    const loadOrders = async () => {
        try {
            setLoading(true);
            const response = await fetch(
                `${import.meta.env.VITE_API_URL || ""}/api/orders`,
                {
                    headers: {
                        ...authHeaders(),
                    },
                }
            );

            if (response.ok) {
                const data = await response.json();
                setOrders(data.orders || []);
            } else {
                throw new Error("Failed to load orders");
            }
        } catch (err) {
            console.error("Failed to load orders:", err);
            setError("Failed to load order history");
        } finally {
            setLoading(false);
        }
    };

    const handleLogout = () => {
        logout();
    };

    return (
        <>
            <Header />
            <div className="container mx-auto px-4 py-8">
                <div className="max-w-4xl mx-auto">
                    {/* Profile Header */}
                    <div className="card p-6 mb-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <h1 className="text-2xl font-semibold text-gray-900">
                                    My Profile
                                </h1>
                                <p className="text-gray-600 mt-1">
                                    {user?.email || "Loading..."}
                                </p>
                            </div>
                            <button
                                onClick={handleLogout}
                                className="btn-secondary"
                            >
                                Sign Out
                            </button>
                        </div>
                    </div>

                    {/* Account Info */}
                    <div className="card p-6 mb-6">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4">
                            Account Information
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address
                                </label>
                                <p className="text-gray-900">
                                    {user?.email || "N/A"}
                                </p>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Member Since
                                </label>
                                <p className="text-gray-900">
                                    {user?.iat
                                        ? new Date(
                                              user.iat * 1000
                                          ).toLocaleDateString()
                                        : "N/A"}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Order History */}
                    <div className="card p-6">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4">
                            Order History
                        </h2>

                        {loading ? (
                            <div className="text-center py-8">
                                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                                <p className="text-gray-600 mt-2">
                                    Loading orders...
                                </p>
                            </div>
                        ) : error ? (
                            <div className="text-center py-8">
                                <p className="text-red-600">{error}</p>
                                <button
                                    onClick={loadOrders}
                                    className="btn-secondary mt-3"
                                >
                                    Try Again
                                </button>
                            </div>
                        ) : orders.length === 0 ? (
                            <div className="text-center py-8">
                                <div className="text-4xl mb-3">📦</div>
                                <p className="text-gray-600">No orders yet</p>
                                <p className="text-sm text-gray-500 mt-1">
                                    Your order history will appear here
                                </p>
                            </div>
                        ) : (
                            <div className="space-y-4">
                                {orders.map((order) => (
                                    <div
                                        key={order.id}
                                        className="border border-gray-200 rounded-lg p-4"
                                    >
                                        <div className="flex items-center justify-between mb-3">
                                            <div>
                                                <h3 className="font-medium text-gray-900">
                                                    Order #
                                                    {order.order_number ||
                                                        order.id}
                                                </h3>
                                                <p className="text-sm text-gray-500">
                                                    {new Date(
                                                        order.created_at
                                                    ).toLocaleDateString()}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <p className="font-semibold text-gray-900">
                                                    $
                                                    {order.total?.toFixed(2) ||
                                                        "0.00"}
                                                </p>
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {order.status ||
                                                        "Completed"}
                                                </span>
                                            </div>
                                        </div>

                                        {order.items &&
                                            order.items.length > 0 && (
                                                <div className="border-t border-gray-200 pt-3">
                                                    <p className="text-sm text-gray-600 mb-2">
                                                        {order.items.length}{" "}
                                                        item
                                                        {order.items.length !==
                                                        1
                                                            ? "s"
                                                            : ""}
                                                    </p>
                                                    <div className="space-y-1">
                                                        {order.items
                                                            .slice(0, 3)
                                                            .map(
                                                                (
                                                                    item,
                                                                    index
                                                                ) => (
                                                                    <p
                                                                        key={
                                                                            index
                                                                        }
                                                                        className="text-sm text-gray-700"
                                                                    >
                                                                        {
                                                                            item.quantity
                                                                        }
                                                                        x{" "}
                                                                        {item
                                                                            .product
                                                                            ?.name ||
                                                                            `Product ${item.product_id}`}
                                                                    </p>
                                                                )
                                                            )}
                                                        {order.items.length >
                                                            3 && (
                                                            <p className="text-sm text-gray-500">
                                                                +
                                                                {order.items
                                                                    .length -
                                                                    3}{" "}
                                                                more items
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
