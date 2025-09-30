import { Link } from "react-router-dom";

/**
 * CartBadge component - displays cart icon with item count
 */
export default function CartBadge({ itemCount = 0 }) {
    return (
        <Link
            to="/cart"
            className="relative flex items-center space-x-2 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded p-2"
            aria-label={`Cart with ${itemCount} items`}
        >
            <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"
                />
            </svg>

            <span className="hidden sm:inline">Cart</span>

            {itemCount > 0 && (
                <span className="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                    {itemCount > 99 ? "99+" : itemCount}
                </span>
            )}
        </Link>
    );
}
