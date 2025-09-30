/**
 * CartContext - Global cart state management (Stretch Goal Preview)
 * This demonstrates the foundation for Week 10's Context API implementation
 */

import { createContext, useContext, useState, useEffect } from "react";
import {
    getCart,
    setCart as saveCart,
    addToCart as addToCartUtil,
    getCartTotals,
} from "../utils/cart";

// Create Cart Context
const CartContext = createContext();

// Custom hook to use cart context
export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error("useCart must be used within a CartProvider");
    }
    return context;
};

// Cart Provider Component
export const CartProvider = ({ children }) => {
    const [cart, setCartState] = useState(getCart());

    // Update localStorage whenever cart changes
    useEffect(() => {
        saveCart(cart);
    }, [cart]);

    // Add item to cart
    const addToCart = (product, qty = 1) => {
        const updatedCart = addToCartUtil(product, qty);
        setCartState(updatedCart);
        return updatedCart;
    };

    // Remove item from cart
    const removeFromCart = (productId) => {
        const updatedCart = {
            ...cart,
            items: cart.items.filter((item) => item.id !== productId),
        };
        setCartState(updatedCart);
        return updatedCart;
    };

    // Update item quantity
    const updateQuantity = (productId, qty) => {
        const updatedCart = {
            ...cart,
            items: cart.items
                .map((item) =>
                    item.id === productId
                        ? { ...item, qty: Math.max(0, qty) }
                        : item
                )
                .filter((item) => item.qty > 0),
        };
        setCartState(updatedCart);
        return updatedCart;
    };

    // Clear entire cart
    const clearCart = () => {
        const emptyCart = { items: [] };
        setCartState(emptyCart);
        return emptyCart;
    };

    // Get cart totals
    const totals = getCartTotals(cart);

    const value = {
        cart,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        totals,
        itemCount: totals.itemCount,
        subtotal: totals.subtotal,
        total: totals.total,
    };

    return (
        <CartContext.Provider value={value}>{children}</CartContext.Provider>
    );
};

export default CartContext;
