/**
 * Authentication utilities for KhmerMart24
 * Handles token storage, retrieval, and API authentication headers
 */

const TOKEN_KEY = "km24:token";

/**
 * Get the current auth token from localStorage
 */
export const getToken = () => {
    return localStorage.getItem(TOKEN_KEY) || "";
};

/**
 * Store auth token in localStorage
 */
export const setToken = (token) => {
    localStorage.setItem(TOKEN_KEY, token);
};

/**
 * Remove auth token from localStorage
 */
export const clearToken = () => {
    localStorage.removeItem(TOKEN_KEY);
};

/**
 * Check if user is authenticated
 */
export const isAuthed = () => {
    return Boolean(getToken());
};

/**
 * Get authorization headers for API requests
 */
export const authHeaders = () => {
    const token = getToken();
    return token ? { Authorization: `Bearer ${token}` } : {};
};

/**
 * Get user info from token (basic JWT decode)
 * Note: This is a simple decode - in production use a proper JWT library
 */
export const getUserFromToken = () => {
    const token = getToken();
    if (!token) return null;

    try {
        const payload = JSON.parse(atob(token.split(".")[1]));
        return payload;
    } catch (error) {
        console.warn("Failed to decode token:", error);
        return null;
    }
};

/**
 * Login API call
 */
export const login = async (email, password) => {
    const response = await fetch(
        `${import.meta.env.VITE_API_URL || ""}/api/login`,
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ email, password }),
        }
    );

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }

    const data = await response.json();

    if (data.token) {
        setToken(data.token);
    }

    return data;
};

/**
 * Logout - clear token and redirect
 */
export const logout = () => {
    clearToken();
    // Could also call logout API endpoint here if needed
    window.location.href = "/";
};
