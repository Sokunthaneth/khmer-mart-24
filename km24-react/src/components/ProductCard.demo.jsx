/**
 * Simple ProductCard component following Week 9 lecture demo pattern
 */
export default function ProductCard({ product, onAdd }) {
  return (
    <div className="border rounded p-3 flex flex-col">
      <img 
        src={product.image_url || product.image} 
        alt={product.name} 
        className="h-40 object-cover mb-2" 
      />
      <h3 className="font-semibold">{product.name}</h3>
      <p className="text-sm text-gray-600">${product.price.toFixed(2)}</p>
      <button 
        onClick={() => onAdd(product)} 
        className="mt-auto bg-blue-600 text-white px-3 py-1 rounded"
      >
        Add to Cart
      </button>
    </div>
  );
}
