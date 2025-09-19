/**
 * DOM utilities and UI helpers for KhmerMart24
 */

/**
 * Show loading state
 */
export function showLoading(element, show = true) {
    if (typeof element === "string") {
        element = document.querySelector(element);
    }

    if (!element) return;

    if (show) {
        element.classList.add("loading");
        element.setAttribute("aria-busy", "true");

        // Add spinner if not present
        if (!element.querySelector(".spinner")) {
            const spinner = createSpinner();
            element.appendChild(spinner);
        }
    } else {
        element.classList.remove("loading");
        element.removeAttribute("aria-busy");

        // Remove spinner
        const spinner = element.querySelector(".spinner");
        if (spinner) {
            spinner.remove();
        }
    }
}

/**
 * Create a loading spinner element
 */
export function createSpinner() {
    const spinner = document.createElement("div");
    spinner.className = "spinner flex items-center justify-center p-4";
    spinner.innerHTML = `
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <span class="sr-only">Loading...</span>
    `;
    return spinner;
}

/**
 * Show error message
 */
export function showError(message, container = null) {
    const errorEl = document.createElement("div");
    errorEl.className =
        "error-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4";
    errorEl.setAttribute("role", "alert");
    errorEl.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.remove()" aria-label="Dismiss error">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (errorEl.parentNode) {
            errorEl.remove();
        }
    }, 5000);

    if (container) {
        if (typeof container === "string") {
            container = document.querySelector(container);
        }
        container.prepend(errorEl);
    } else {
        // Find a good place to show the error
        const main = document.querySelector("main");
        if (main) {
            main.prepend(errorEl);
        }
    }

    return errorEl;
}

/**
 * Show success message
 */
export function showSuccess(message, container = null) {
    const successEl = document.createElement("div");
    successEl.className =
        "success-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4";
    successEl.setAttribute("role", "alert");
    successEl.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.remove()" aria-label="Dismiss message">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;

    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (successEl.parentNode) {
            successEl.remove();
        }
    }, 3000);

    if (container) {
        if (typeof container === "string") {
            container = document.querySelector(container);
        }
        container.prepend(successEl);
    } else {
        const main = document.querySelector("main");
        if (main) {
            main.prepend(successEl);
        }
    }

    return successEl;
}

/**
 * Debounce function calls
 */
export function debounce(func, wait, immediate = false) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

/**
 * Format currency
 */
export function formatCurrency(amount, currency = "USD") {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency,
    }).format(amount);
}

/**
 * Escape HTML
 */
export function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
