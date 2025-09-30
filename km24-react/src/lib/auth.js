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
 * Check if token is expired
 */
export const isTokenExpired = (token = getToken()) => {
    if (!token) return true;

    try {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const currentTime = Date.now() / 1000;

        // Token is expired if current time is past expiry time
        return payload.exp && payload.exp < currentTime;
    } catch (error) {
        console.warn("Failed to decode token:", error);
        return true; // Treat invalid tokens as expired
    }
};

/**
 * Check if user is authenticated and token is valid
 */
export const isAuthed = () => {
    const token = getToken();
    if (!token) return false;

    // Check if token is expired
    return !isTokenExpired(token);
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
 * Refresh auth token (Stretch Goal)
 */
export const refreshToken = async () => {
    const currentToken = getToken();
    if (!currentToken) {
        throw new Error("No token to refresh");
    }

    try {
        const response = await fetch(
            `${import.meta.env.VITE_API_URL || ""}/api/refresh`,
            {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${currentToken}`,
                    "Content-Type": "application/json",
                },
            }
        );

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();

        if (data.token) {
            setToken(data.token);
            return data.token;
        }

        throw new Error("No token in refresh response");
    } catch (error) {
        console.error("Token refresh failed:", error);
        // If refresh fails, logout the user
        logout();
        throw error;
    }
};

/**
 * Auto-refresh token if it's close to expiry (Stretch Goal)
 */
export const autoRefreshToken = async () => {
    const token = getToken();
    if (!token) return;

    try {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const currentTime = Date.now() / 1000;
        const timeUntilExpiry = payload.exp - currentTime;

        // Refresh if token expires in less than 5 minutes
        if (timeUntilExpiry < 300 && timeUntilExpiry > 0) {
            console.log("Auto-refreshing token...");
            await refreshToken();
        }
    } catch (error) {
        console.warn("Auto-refresh check failed:", error);
    }
};

/**
 * Enhanced authHeaders with auto-refresh (Stretch Goal)
 */
export const authHeadersWithRefresh = async () => {
    await autoRefreshToken();
    return authHeaders();
};

/**
 * Logout - clear token and redirect
 */
export const logout = () => {
    clearToken();
    // Could also call logout API endpoint here if needed
    window.location.href = "/";
};
