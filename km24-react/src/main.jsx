import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import "./index.css";

// Import pages
import HomePage from "./pages/HomePage.jsx";
import CartPage from "./pages/CartPage.jsx";
import CheckoutPage from "./pages/CheckoutPage.jsx";
import ProfilePage from "./pages/ProfilePage.jsx";
import LoginPage from "./pages/LoginPage.jsx";

// Import components
import RequireAuth from "./components/RequireAuth.jsx";

// Define routes
const router = createBrowserRouter([
    {
        path: "/",
        element: <HomePage />,
    },
    {
        path: "/cart",
        element: <CartPage />,
    },
    {
        path: "/login",
        element: <LoginPage />,
    },
    {
        path: "/checkout",
        element: (
            <RequireAuth>
                <CheckoutPage />
            </RequireAuth>
        ),
    },
    {
        path: "/profile",
        element: (
            <RequireAuth>
                <ProfilePage />
            </RequireAuth>
        ),
    },
]);

createRoot(document.getElementById("root")).render(
    <StrictMode>
        <RouterProvider router={router} />
    </StrictMode>
);
