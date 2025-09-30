/**
 * API utility functions for data fetching
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
 * Load products from the API with fallback to static data
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
        try {
            const response = await fetch("/products.json");
            if (!response.ok) {
                throw new Error("Failed to load products from fallback");
            }
            return await response.json();
        } catch (fallbackError) {
            // If both API and fallback fail, return mock data
            console.warn(
                "Fallback JSON also failed, using mock data:",
                fallbackError
            );
            return getMockProducts();
        }
    }
}

/**
 * Load a single product by ID
 */
export async function loadProduct(id) {
    try {
        return await apiRequest(`/products/${id}`);
    } catch (error) {
        // Fallback logic could be added here
        throw error;
    }
}

/**
 * Load categories
 */
export async function loadCategories() {
    try {
        return await apiRequest("/categories");
    } catch (error) {
        console.warn(
            "Categories API not available, falling back to static data"
        );
        return getMockCategories();
    }
}

/**
 * Mock products for development
 */
function getMockProducts() {
    return {
        data: [
            {
                id: 1,
                name: "Wireless Bluetooth Headphones",
                description:
                    "Premium quality wireless headphones with active noise cancellation and 30-hour battery life.",
                price: 99.99,
                image_url:
                    "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop&crop=center",
                category_id: 1,
            },
            {
                id: 2,
                name: "Smart Fitness Watch",
                description:
                    "Track your health and fitness with this advanced smartwatch featuring GPS, heart rate monitoring, and sleep tracking.",
                price: 199.99,
                image_url:
                    "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop&crop=center",
                category_id: 1,
            },
            {
                id: 3,
                name: "Ergonomic Laptop Stand",
                description:
                    "Adjustable aluminum laptop stand for better posture and improved airflow. Compatible with all laptop sizes.",
                price: 49.99,
                image_url:
                    "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=400&fit=crop&crop=center",
                category_id: 2,
            },
            {
                id: 4,
                name: "Organic Cotton T-Shirt",
                description:
                    "Comfortable and sustainable organic cotton t-shirt available in multiple colors and sizes.",
                price: 24.99,
                image_url:
                    "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop&crop=center",
                category_id: 3,
            },
            {
                id: 5,
                name: "Wireless Phone Charger",
                description:
                    "Fast wireless charging pad compatible with Qi-enabled devices. Includes LED indicator and non-slip base.",
                price: 34.99,
                image_url:
                    "https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=400&h=400&fit=crop&crop=center",
                category_id: 1,
            },
            {
                id: 6,
                name: "Ceramic Coffee Mug Set",
                description:
                    "Set of 4 handcrafted ceramic coffee mugs with unique glazed finish. Microwave and dishwasher safe.",
                price: 39.99,
                image_url:
                    "https://images.unsplash.com/photo-1514228742587-6b1558fcf93a?w=400&h=400&fit=crop&crop=center",
                category_id: 2,
            },
        ],
        meta: {
            current_page: 1,
            from: 1,
            to: 6,
            total: 6,
            per_page: 12,
            last_page: 1,
        },
    };
}

/**
 * Mock categories for development
 */
function getMockCategories() {
    return [
        {
            id: 1,
            name: "Electronics",
            description: "Latest gadgets and electronic devices",
        },
        {
            id: 2,
            name: "Office & Home",
            description: "Home and office essentials",
        },
        { id: 3, name: "Fashion", description: "Clothing and accessories" },
        {
            id: 4,
            name: "Sports & Fitness",
            description: "Equipment for active lifestyle",
        },
    ];
}
