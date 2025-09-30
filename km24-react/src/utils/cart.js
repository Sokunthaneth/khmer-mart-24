/**
 * Cart utility functions for localStorage management
 */

const CART_KEY = "km24:cart";

/**
 * Get cart from localStorage
 */
export function getCart() {
    try {
        const stored = localStorage.getItem(CART_KEY);
        return stored ? JSON.parse(stored) : { items: [] };
    } catch (error) {
        console.error("Failed to parse cart from localStorage:", error);
        return { items: [] };
    }
}

/**
 * Save cart to localStorage
 */
export function setCart(cart) {
    try {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
    } catch (error) {
        console.error("Failed to save cart to localStorage:", error);
    }
}

/**
 * Add item to cart
 */
export function addToCart(product, qty = 1) {
    const cart = getCart();
    const existingIndex = cart.items.findIndex(
        (item) => item.id === product.id
    );

    if (existingIndex >= 0) {
        cart.items[existingIndex].qty += qty;
    } else {
        cart.items.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image_url || product.image,
            qty: qty,
        });
    }

    setCart(cart);
    return cart;
}

/**
 * Remove item from cart
 */
export function removeFromCart(productId) {
    const cart = getCart();
    cart.items = cart.items.filter((item) => item.id !== productId);
    setCart(cart);
    return cart;
}

/**
 * Update item quantity in cart
 */
export function updateCartQuantity(productId, qty) {
    const cart = getCart();
    const itemIndex = cart.items.findIndex((item) => item.id === productId);

    if (itemIndex >= 0) {
        if (qty <= 0) {
            cart.items.splice(itemIndex, 1);
        } else {
            cart.items[itemIndex].qty = qty;
        }
        setCart(cart);
    }

    return cart;
}

/**
 * Clear entire cart
 */
export function clearCart() {
    const emptyCart = { items: [] };
    setCart(emptyCart);
    return emptyCart;
}

/**
 * Get cart totals
 */
export function getCartTotals(cart = getCart()) {
    const subtotal = cart.items.reduce(
        (total, item) => total + item.price * item.qty,
        0
    );
    const itemCount = cart.items.reduce((count, item) => count + item.qty, 0);

    return {
        subtotal: subtotal,
        itemCount: itemCount,
        total: subtotal, // Add tax, shipping, etc. here later
    };
}
