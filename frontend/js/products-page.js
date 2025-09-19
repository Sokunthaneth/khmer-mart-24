/**
 * Main application entry point for the products page
 */

import { loadProducts, loadCategories } from "./api.js";
import { initCart } from "./cart.js";
import {
    renderProductGrid,
    setupProductInteractions,
    setupSearch,
    setupCategoryFilter,
} from "./products.js";
import { showLoading, showError, debounce } from "./utils.js";

// State management
let currentFilters = {
    search: "",
    category: "",
    page: 1,
};

/**
 * Initialize the products page
 */
async function initProductsPage() {
    console.log("Initializing products page...");

    // Initialize cart
    initCart();

    // Setup interactions
    const productGrid = document.querySelector(".product-grid");
    if (productGrid) {
        setupProductInteractions(productGrid);
    }

    // Setup search
    setupSearch("#search", handleSearch);

    // Setup category filter
    setupCategoryFilter("#category-filter", handleCategoryChange);

    // Load initial data
    await Promise.all([loadAllProducts(), loadCategoryOptions()]);
}

/**
 * Load and display all products
 */
async function loadAllProducts() {
    const productGrid = document.querySelector(".product-grid");
    const resultsInfo = document.querySelector("[data-results-info]");

    if (!productGrid) return;

    try {
        showLoading(productGrid, true);

        // Build query parameters
        const params = {};
        if (currentFilters.search) {
            params.search = currentFilters.search;
        }
        if (currentFilters.category) {
            params.category_id = currentFilters.category;
        }
        params.page = currentFilters.page;

        const response = await loadProducts(params);
        const products = response.data || response;

        showLoading(productGrid, false);
        renderProductGrid(products, productGrid);

        // Update results info
        if (resultsInfo && response.meta) {
            const { from, to, total } = response.meta;
            resultsInfo.textContent = `Showing ${from}-${to} of ${total} products`;
        }

        console.log(`Loaded ${products.length} products`);
    } catch (error) {
        console.error("Failed to load products:", error);
        showLoading(productGrid, false);
        showError("Failed to load products. Please try again.");
    }
}

/**
 * Load category options for filter
 */
async function loadCategoryOptions() {
    const categorySelect = document.querySelector("#category-filter");
    if (!categorySelect) return;

    try {
        const categories = await loadCategories();

        // Clear existing options (keep the "All Categories" option)
        const defaultOption = categorySelect.querySelector('option[value=""]');
        categorySelect.innerHTML = "";
        if (defaultOption) {
            categorySelect.appendChild(defaultOption);
        } else {
            categorySelect.innerHTML =
                '<option value="">All Categories</option>';
        }

        // Add category options
        categories.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });

        console.log(`Loaded ${categories.length} categories`);
    } catch (error) {
        console.error("Failed to load categories:", error);
        // Don't show error for categories as it's not critical
    }
}

/**
 * Handle search input
 */
const handleSearch = debounce((searchTerm) => {
    currentFilters.search = searchTerm;
    currentFilters.page = 1; // Reset to first page
    loadAllProducts();
}, 300);

/**
 * Handle category filter change
 */
function handleCategoryChange(categoryId) {
    currentFilters.category = categoryId;
    currentFilters.page = 1; // Reset to first page
    loadAllProducts();
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initProductsPage);
} else {
    initProductsPage();
}
