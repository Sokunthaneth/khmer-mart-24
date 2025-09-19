/**
 * Cart functionality for KhmerMart24
 * Handles cart state, localStorage persistence, and UI updates
 */

const CART_KEY = "km24:cart";

/**
 * Cart state structure:
 * {
 *   items: [
 *     { id, name, price, image, qty }
 *   ]
 * }
 */

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
        updateCartBadge(cart);
        dispatchCartUpdate(cart);
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
            image: product.image || product.image_url,
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

/**
 * Update cart badge in navigation
 */
export function updateCartBadge(cart = getCart()) {
    const badge = document.querySelector("[data-cart-count]");
    if (badge) {
        const { itemCount } = getCartTotals(cart);
        badge.textContent = String(itemCount);

        // Add visual feedback
        badge.classList.add("animate-pulse");
        setTimeout(() => badge.classList.remove("animate-pulse"), 300);
    }
}

/**
 * Dispatch cart update event for other components to listen
 */
function dispatchCartUpdate(cart) {
    const event = new CustomEvent("cartUpdated", {
        detail: { cart, totals: getCartTotals(cart) },
    });
    document.dispatchEvent(event);
}

/**
 * Initialize cart on page load
 */
export function initCart() {
    updateCartBadge();

    // Listen for storage changes from other tabs
    window.addEventListener("storage", (e) => {
        if (e.key === CART_KEY) {
            updateCartBadge();
        }
    });
}
