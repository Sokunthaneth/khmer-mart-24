/**
 * Cart page functionality for KhmerMart24
 */

import {
    getCart,
    removeFromCart,
    updateCartQuantity,
    clearCart,
    getCartTotals,
    initCart,
} from "./cart.js";
import { formatCurrency, escapeHtml, showSuccess, showError } from "./utils.js";

/**
 * Initialize the cart page
 */
function initCartPage() {
    console.log("Initializing cart page...");

    // Initialize cart functionality
    initCart();

    // Set up event listeners
    setupCartEventListeners();

    // Render cart
    renderCart();

    // Listen for cart updates
    document.addEventListener("cartUpdated", () => {
        renderCart();
    });
}

/**
 * Set up event listeners for cart interactions
 */
function setupCartEventListeners() {
    const cartItems = document.getElementById("cart-items");
    const checkoutBtn = document.getElementById("checkout-btn");

    // Cart item interactions
    if (cartItems) {
        cartItems.addEventListener("click", handleCartItemClick);
        cartItems.addEventListener("change", handleQuantityChange);
    }

    // Checkout button
    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", handleCheckout);
    }
}

/**
 * Handle clicks on cart items (remove buttons)
 */
function handleCartItemClick(e) {
    const removeBtn = e.target.closest("[data-remove-item]");
    if (!removeBtn) return;

    e.preventDefault();

    const productId = parseInt(removeBtn.dataset.productId);
    if (productId) {
        removeFromCart(productId);
        showSuccess("Item removed from cart");
    }
}

/**
 * Handle quantity changes
 */
function handleQuantityChange(e) {
    const quantityInput = e.target.closest("[data-quantity-input]");
    if (!quantityInput) return;

    const productId = parseInt(quantityInput.dataset.productId);
    const newQuantity = parseInt(quantityInput.value);

    if (productId && newQuantity >= 0) {
        updateCartQuantity(productId, newQuantity);
    }
}

/**
 * Handle checkout process
 */
function handleCheckout(e) {
    e.preventDefault();

    const cart = getCart();
    if (cart.items.length === 0) {
        showError("Your cart is empty");
        return;
    }

    // For now, just show a success message
    showSuccess(
        "Checkout functionality coming soon! Your cart has been saved."
    );

    // In a real implementation, this would redirect to a checkout page
    // or open a checkout modal
}

/**
 * Render the entire cart
 */
function renderCart() {
    const cart = getCart();
    const emptyCart = document.getElementById("empty-cart");
    const cartItems = document.getElementById("cart-items");
    const checkoutBtn = document.getElementById("checkout-btn");

    if (!cartItems) return;

    if (cart.items.length === 0) {
        // Show empty cart state
        if (emptyCart) emptyCart.classList.remove("hidden");
        cartItems.innerHTML = "";
        if (checkoutBtn) checkoutBtn.disabled = true;
    } else {
        // Hide empty cart state
        if (emptyCart) emptyCart.classList.add("hidden");
        if (checkoutBtn) checkoutBtn.disabled = false;

        // Render cart items
        cartItems.innerHTML = cart.items
            .map((item) => renderCartItem(item))
            .join("");
    }

    // Update totals
    updateOrderSummary(cart);
}

/**
 * Render a single cart item
 */
function renderCartItem(item) {
    const imageUrl =
        item.image ||
        "https://via.placeholder.com/100x100/e5e7eb/6b7280?text=Product";
    const itemTotal = item.price * item.qty;

    return `
        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg" data-item-id="${
            item.id
        }">
            <!-- Product Image -->
            <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                <img
                    src="${escapeHtml(imageUrl)}"
                    alt="${escapeHtml(item.name)}"
                    class="w-full h-full object-cover"
                />
            </div>

            <!-- Product Info -->
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-gray-900 truncate">${escapeHtml(
                    item.name
                )}</h3>
                <p class="text-gray-600 text-sm">${formatCurrency(
                    item.price
                )} each</p>
            </div>

            <!-- Quantity Controls -->
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50"
                    onclick="this.nextElementSibling.stepDown(); this.nextElementSibling.dispatchEvent(new Event('change', { bubbles: true }));"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </button>

                <input
                    type="number"
                    min="0"
                    max="99"
                    value="${item.qty}"
                    class="w-16 text-center border border-gray-300 rounded px-2 py-1"
                    data-quantity-input
                    data-product-id="${item.id}"
                />

                <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-50"
                    onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.dispatchEvent(new Event('change', { bubbles: true }));"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>

            <!-- Item Total -->
            <div class="text-right min-w-0">
                <p class="font-medium">${formatCurrency(itemTotal)}</p>
                <button
                    type="button"
                    class="text-red-600 hover:text-red-800 text-sm mt-1"
                    data-remove-item
                    data-product-id="${item.id}"
                >
                    Remove
                </button>
            </div>
        </div>
    `;
}

/**
 * Update the order summary section
 */
function updateOrderSummary(cart) {
    const totals = getCartTotals(cart);

    const subtotalEl = document.getElementById("subtotal");
    const taxEl = document.getElementById("tax");
    const totalEl = document.getElementById("total");

    if (subtotalEl) subtotalEl.textContent = formatCurrency(totals.subtotal);
    if (taxEl) taxEl.textContent = formatCurrency(0); // No tax calculation yet
    if (totalEl) totalEl.textContent = formatCurrency(totals.total);
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCartPage);
} else {
    initCartPage();
}
