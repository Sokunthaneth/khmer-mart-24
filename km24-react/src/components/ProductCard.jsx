import { useState } from "react";

/**
 * ProductCard component - displays a single product with add to cart functionality
 */
export default function ProductCard({ product, onAdd }) {
    const [isAdding, setIsAdding] = useState(false);

    const handleAddToCart = async () => {
        if (isAdding) return;

        setIsAdding(true);
        try {
            await onAdd(product);
        } finally {
            setIsAdding(false);
        }
    };

    const imageUrl =
        product.image_url ||
        product.image ||
        "https://via.placeholder.com/300x300/e5e7eb/6b7280?text=Product";
    const price =
        typeof product.price === "number" ? product.price.toFixed(2) : "0.00";

    return (
        <article className="card group">
            <div className="aspect-square bg-gray-200 overflow-hidden">
                <img
                    src={imageUrl}
                    alt={product.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    loading="lazy"
                />
            </div>

            <div className="p-4">
                <h3 className="font-medium text-lg mb-2 text-gray-900 line-clamp-2">
                    {product.name}
                </h3>

                {product.description && (
                    <p className="text-gray-600 text-sm mb-3 line-clamp-2">
                        {product.description}
                    </p>
                )}

                <p className="text-indigo-700 font-bold text-xl mb-4">
                    ${price}
                </p>

                <div className="flex gap-2">
                    <button
                        onClick={handleAddToCart}
                        disabled={isAdding}
                        className={`btn-primary flex-1 ${
                            isAdding ? "opacity-75 cursor-not-allowed" : ""
                        }`}
                    >
                        {isAdding ? "Adding..." : "Add to Cart"}
                    </button>

                    <button className="btn-secondary">Details</button>
                </div>
            </div>
        </article>
    );
}
