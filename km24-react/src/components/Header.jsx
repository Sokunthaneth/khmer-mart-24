import { Link } from "react-router-dom";
import { isAuthed, logout } from "../lib/auth";
import CartBadge from "./CartBadge";

/**
 * Header component - main navigation with logo, links, and cart
 */
export default function Header({ cartItemCount, onCartClick }) {
    const authenticated = isAuthed();

    const handleLogout = () => {
        logout();
    };

    return (
        <header className="border-b bg-white shadow-sm">
            <nav className="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
                {/* Logo */}
                <div className="flex items-center">
                    <Link
                        to="/"
                        className="text-xl font-bold text-indigo-600 hover:text-indigo-700"
                    >
                        KhmerMart24
                    </Link>
                </div>

                {/* Navigation Links */}
                <div className="hidden md:flex items-center space-x-6">
                    <Link
                        to="/"
                        className="text-gray-900 hover:text-indigo-600 font-medium"
                    >
                        Home
                    </Link>
                    <Link
                        to="/cart"
                        className="text-gray-900 hover:text-indigo-600 font-medium"
                    >
                        Cart
                    </Link>
                    {authenticated ? (
                        <>
                            <Link
                                to="/profile"
                                className="text-gray-900 hover:text-indigo-600 font-medium"
                            >
                                Profile
                            </Link>
                            <button
                                onClick={handleLogout}
                                className="text-gray-900 hover:text-indigo-600 font-medium"
                            >
                                Sign Out
                            </button>
                        </>
                    ) : (
                        <Link
                            to="/login"
                            className="text-gray-900 hover:text-indigo-600 font-medium"
                        >
                            Sign In
                        </Link>
                    )}
                </div>

                {/* Cart Badge */}
                <CartBadge itemCount={cartItemCount} onClick={onCartClick} />
            </nav>
        </header>
    );
}
