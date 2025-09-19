/**
 * Main application entry point for the home page
 */

import { loadProducts } from "./api.js";
import { initCart } from "./cart.js";
import { renderProductGrid, setupProductInteractions } from "./products.js";
import { showLoading, showError } from "./utils.js";

/**
 * Initialize the home page
 */
async function initHomePage() {
    console.log("Initializing home page...");

    // Initialize cart
    initCart();

    // Setup product interactions
    const productGrid = document.querySelector(".product-grid");
    if (productGrid) {
        setupProductInteractions(productGrid);
        await loadFeaturedProducts();
    }
}

/**
 * Load featured products for the home page
 */
async function loadFeaturedProducts() {
    const productGrid = document.querySelector(".product-grid");
    if (!productGrid) return;

    try {
        showLoading(productGrid, true);

        // Load first 6 products as featured
        const response = await loadProducts({ per_page: 6 });
        const products = response.data || response;

        showLoading(productGrid, false);
        renderProductGrid(products, productGrid);

        console.log(`Loaded ${products.length} featured products`);
    } catch (error) {
        console.error("Failed to load featured products:", error);
        showLoading(productGrid, false);
        showError("Failed to load featured products. Please refresh the page.");
    }
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initHomePage);
} else {
    initHomePage();
}
