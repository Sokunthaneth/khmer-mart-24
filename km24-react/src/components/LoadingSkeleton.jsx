/**
 * LoadingSkeleton - Animated loading placeholder (Stretch Goal)
 * Provides smooth loading experience while products are being fetched
 */

export default function LoadingSkeleton({ count = 6 }) {
    return (
        <div className="product-grid">
            {Array.from({ length: count }).map((_, index) => (
                <div key={index} className="card animate-pulse">
                    {/* Image Skeleton */}
                    <div className="aspect-square bg-gray-300 rounded-t-lg"></div>

                    <div className="p-4">
                        {/* Title Skeleton */}
                        <div className="h-4 bg-gray-300 rounded mb-2"></div>
                        <div className="h-4 bg-gray-300 rounded w-3/4 mb-3"></div>

                        {/* Description Skeleton */}
                        <div className="h-3 bg-gray-200 rounded mb-1"></div>
                        <div className="h-3 bg-gray-200 rounded w-2/3 mb-3"></div>

                        {/* Price Skeleton */}
                        <div className="h-6 bg-gray-300 rounded w-1/3 mb-4"></div>

                        {/* Button Skeletons */}
                        <div className="flex gap-2">
                            <div className="h-10 bg-gray-300 rounded flex-1"></div>
                            <div className="h-10 bg-gray-200 rounded w-20"></div>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}

/**
 * Usage example in ProductGrid:
 *
 * if (loading) {
 *   return <LoadingSkeleton count={6} />;
 * }
 */
