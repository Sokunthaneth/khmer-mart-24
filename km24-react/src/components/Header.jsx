import CartBadge from "./CartBadge";

/**
 * Header component - main navigation with logo and cart
 */
export default function Header({ cartItemCount, onCartClick }) {
    return (
        <header className="border-b bg-white shadow-sm">
            <nav className="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
                {/* Logo */}
                <div className="flex items-center">
                    <h1 className="text-xl font-bold text-indigo-600 hover:text-indigo-700">
                        KhmerMart24
                    </h1>
                </div>

                {/* Navigation Links */}
                <div className="hidden md:flex items-center space-x-6">
                    <a
                        href="#"
                        className="text-gray-900 hover:text-indigo-600 font-medium"
                    >
                        Home
                    </a>
                    <a
                        href="#"
                        className="text-gray-900 hover:text-indigo-600 font-medium"
                    >
                        Products
                    </a>
                    <a
                        href="#"
                        className="text-gray-900 hover:text-indigo-600 font-medium"
                    >
                        About
                    </a>
                </div>

                {/* Cart Badge */}
                <CartBadge itemCount={cartItemCount} onClick={onCartClick} />
            </nav>
        </header>
    );
}
