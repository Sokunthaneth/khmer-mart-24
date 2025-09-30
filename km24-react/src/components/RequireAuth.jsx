import { Navigate, useLocation } from "react-router-dom";
import { isAuthed } from "../lib/auth";

/**
 * RequireAuth - Route guard component
 * Redirects to login if user is not authenticated
 */
export default function RequireAuth({ children }) {
    const location = useLocation();
    const authenticated = isAuthed();

    if (!authenticated) {
        // Redirect to login page but save the attempted location
        return <Navigate to="/login" state={{ from: location }} replace />;
    }

    return children;
}
