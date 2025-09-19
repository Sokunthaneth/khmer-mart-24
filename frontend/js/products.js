/**
 * Product rendering and interaction functionality
 */

import { addToCart, getCart } from "./cart.js";
import {
    showLoading,
    showError,
    showSuccess,
    formatCurrency,
    escapeHtml,
} from "./utils.js";

/**
 * Render a single product card
 */
export function renderProductCard(product) {
    const imageUrl =
        product.image_url ||
        product.image ||
        "https://via.placeholder.com/300x300/e5e7eb/6b7280?text=Product";
    const price = formatCurrency(product.price);

    return `
        <article class="card group" data-product-id="${product.id}">
            <div class="aspect-square rounded-lg bg-gray-200 mb-4 overflow-hidden">
                <img
                    src="${escapeHtml(imageUrl)}"
                    alt="${escapeHtml(product.name)}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                />
            </div>
            <h3 class="font-medium text-lg mb-2">${escapeHtml(
                product.name
            )}</h3>
            <p class="text-gray-600 text-sm mb-3">${escapeHtml(
                product.description || ""
            )}</p>
            <p class="text-indigo-700 font-bold text-xl mb-4">${price}</p>
            <div class="flex gap-2">
                <button
                    type="button"
                    class="btn-primary flex-1"
                    data-add-to-cart
                    data-product-id="${product.id}"
                    data-product-name="${escapeHtml(product.name)}"
                    data-product-price="${product.price}"
                    data-product-image="${escapeHtml(imageUrl)}"
                >
                    Add to Cart
                </button>
                <a
                    href="pages/product.html?id=${product.id}"
                    class="btn-secondary"
                    aria-label="View details for ${escapeHtml(product.name)}"
                >
                    Details
                </a>
            </div>
        </article>
    `;
}

/**
 * Render multiple products in a grid
 */
export function renderProductGrid(products, container) {
    if (typeof container === "string") {
        container = document.querySelector(container);
    }

    if (!container) {
        console.error("Product grid container not found");
        return;
    }

    if (!products || products.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-gray-500">Try adjusting your search or filters.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = products
        .map((product) => renderProductCard(product))
        .join("");
}

/**
 * Handle add to cart clicks using event delegation
 */
export function setupProductInteractions(container) {
    if (typeof container === "string") {
        container = document.querySelector(container);
    }

    if (!container) {
        console.error("Product container not found");
        return;
    }

    container.addEventListener("click", async (e) => {
        const addButton = e.target.closest("[data-add-to-cart]");
        if (!addButton) return;

        e.preventDefault();

        // Prevent double clicks
        if (addButton.disabled) return;
        addButton.disabled = true;

        try {
            const productData = {
                id: parseInt(addButton.dataset.productId),
                name: addButton.dataset.productName,
                price: parseFloat(addButton.dataset.productPrice),
                image: addButton.dataset.productImage,
            };

            // Add visual feedback
            const originalText = addButton.textContent;
            addButton.textContent = "Adding...";
            addButton.classList.add("opacity-75");

            // Add to cart
            const cart = addToCart(productData, 1);

            // Show success feedback
            showSuccess(`${productData.name} added to cart!`);

            // Brief visual feedback on button
            addButton.textContent = "Added!";
            addButton.classList.remove("opacity-75");
            addButton.classList.add("bg-green-600");

            setTimeout(() => {
                addButton.textContent = originalText;
                addButton.classList.remove("bg-green-600");
                addButton.disabled = false;
            }, 1000);
        } catch (error) {
            console.error("Failed to add to cart:", error);
            showError("Failed to add item to cart. Please try again.");

            addButton.textContent = "Add to Cart";
            addButton.classList.remove("opacity-75");
            addButton.disabled = false;
        }
    });
}

/**
 * Setup search functionality
 */
export function setupSearch(searchInput, onSearch) {
    if (typeof searchInput === "string") {
        searchInput = document.querySelector(searchInput);
    }

    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener("input", (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            onSearch(e.target.value.trim());
        }, 300); // Debounce search
    });
}

/**
 * Setup category filters
 */
export function setupCategoryFilter(categorySelect, onCategoryChange) {
    if (typeof categorySelect === "string") {
        categorySelect = document.querySelector(categorySelect);
    }

    if (!categorySelect) return;

    categorySelect.addEventListener("change", (e) => {
        onCategoryChange(e.target.value);
    });
}
