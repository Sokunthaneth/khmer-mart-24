/**
 * API utilities for KhmerMart24
 * Handles all communication with the Laravel backend
 */

const API_BASE_URL = "/api";

/**
 * Generic fetch wrapper with error handling
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;

    try {
        const response = await fetch(url, {
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                ...options.headers,
            },
            ...options,
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return await response.json();
    } catch (error) {
        console.error("API Request failed:", error);
        throw error;
    }
}

/**
 * Load products from the API
 */
export async function loadProducts(params = {}) {
    const searchParams = new URLSearchParams(params);
    const endpoint = `/products${
        searchParams.toString() ? "?" + searchParams.toString() : ""
    }`;

    try {
        return await apiRequest(endpoint);
    } catch (error) {
        console.warn(
            "Laravel API not available, falling back to static JSON:",
            error
        );
        // Fallback to static JSON file for development
        const response = await fetch("./products.json");
        if (!response.ok) {
            throw new Error("Failed to load products from fallback");
        }
        return await response.json();
    }
}

/**
 * Load a single product by ID
 */
export async function loadProduct(id) {
    return await apiRequest(`/products/${id}`);
}

/**
 * Load categories
 */
export async function loadCategories() {
    try {
        return await apiRequest("/categories");
    } catch (error) {
        console.warn(
            "Laravel API not available, falling back to static JSON:",
            error
        );
        // Fallback to static JSON file for development
        const response = await fetch("./categories.json");
        if (!response.ok) {
            throw new Error("Failed to load categories from fallback");
        }
        return await response.json();
    }
}

/**
 * Health check endpoint
 */
export async function healthCheck() {
    return await apiRequest("/health");
}
