/**
 * Test script for Week 8 JavaScript functionality
 * Open browser console and run: loadScript('js/test.js')
 */

// Test utilities
function log(message, type = "info") {
    const styles = {
        info: "color: blue",
        success: "color: green",
        error: "color: red",
        warn: "color: orange",
    };
    console.log(`%c[TEST] ${message}`, styles[type]);
}

function assert(condition, message) {
    if (condition) {
        log(`✓ ${message}`, "success");
    } else {
        log(`✗ ${message}`, "error");
        throw new Error(`Assertion failed: ${message}`);
    }
}

// Test API functions
async function testAPI() {
    log("Testing API functions...");

    try {
        const { loadProducts, loadCategories } = await import("./api.js");

        // Test product loading
        const products = await loadProducts();
        assert(Array.isArray(products.data), "Products should be an array");
        assert(products.data.length > 0, "Should load at least one product");

        // Test category loading
        const categories = await loadCategories();
        assert(Array.isArray(categories), "Categories should be an array");

        log("API tests passed!", "success");
    } catch (error) {
        log(`API test failed: ${error.message}`, "error");
    }
}

// Test cart functions
async function testCart() {
    log("Testing cart functions...");

    try {
        const { getCart, addToCart, removeFromCart, clearCart, getCartTotals } =
            await import("./cart.js");

        // Clear cart first
        clearCart();
        let cart = getCart();
        assert(cart.items.length === 0, "Cart should be empty after clear");

        // Test adding item
        const testProduct = {
            id: 999,
            name: "Test Product",
            price: 19.99,
            image: "test.jpg",
        };

        addToCart(testProduct, 2);
        cart = getCart();
        assert(cart.items.length === 1, "Should have one item after adding");
        assert(cart.items[0].qty === 2, "Quantity should be 2");

        // Test totals
        const totals = getCartTotals(cart);
        assert(totals.subtotal === 39.98, "Subtotal should be 39.98");
        assert(totals.itemCount === 2, "Item count should be 2");

        // Test removing item
        removeFromCart(999);
        cart = getCart();
        assert(cart.items.length === 0, "Cart should be empty after removal");

        log("Cart tests passed!", "success");
    } catch (error) {
        log(`Cart test failed: ${error.message}`, "error");
    }
}

// Test UI utilities
async function testUI() {
    log("Testing UI utilities...");

    try {
        const { formatCurrency, escapeHtml, debounce } = await import(
            "./utils.js"
        );

        // Test currency formatting
        assert(
            formatCurrency(19.99) === "$19.99",
            "Currency formatting should work"
        );

        // Test HTML escaping
        assert(
            escapeHtml("<script>") === "&lt;script&gt;",
            "HTML escaping should work"
        );

        // Test debounce
        let counter = 0;
        const debouncedFn = debounce(() => counter++, 100);
        debouncedFn();
        debouncedFn();
        debouncedFn();

        setTimeout(() => {
            assert(counter === 1, "Debounce should only call function once");
            log("UI tests passed!", "success");
        }, 150);
    } catch (error) {
        log(`UI test failed: ${error.message}`, "error");
    }
}

// Test DOM interactions
function testDOM() {
    log("Testing DOM interactions...");

    try {
        // Test cart badge exists
        const cartBadge = document.querySelector("[data-cart-count]");
        assert(cartBadge !== null, "Cart badge should exist in DOM");

        // Test product grid exists
        const productGrid = document.querySelector(".product-grid");
        assert(productGrid !== null, "Product grid should exist in DOM");

        log("DOM tests passed!", "success");
    } catch (error) {
        log(`DOM test failed: ${error.message}`, "error");
    }
}

// Run all tests
async function runAllTests() {
    log("🧪 Starting Week 8 JavaScript Tests...", "info");

    testDOM();
    await testAPI();
    await testCart();
    await testUI();

    log("🎉 All tests completed!", "success");
}

// Auto-run if script is loaded
if (typeof window !== "undefined") {
    runAllTests();
}

// Export for manual testing
window.testWeek8 = {
    runAllTests,
    testAPI,
    testCart,
    testUI,
    testDOM,
};
