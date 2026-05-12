import { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'
import { useCart } from '../../context/useCart'

export default function ProductDetailPage() {
  const { vendorSlug, productId } = useParams()
  const { addItem } = useCart()
  const [product, setProduct] = useState(null)

  useEffect(() => {
    api.get(`/store/${vendorSlug}/products/${productId}`).then((response) => setProduct(response.data))
  }, [productId, vendorSlug])

  if (!product) return <div className="card">Loading product...</div>

  function addProduct() {
    addItem(product)
    toast.success(`${product.name} added to cart`)
  }

  // Get category-specific gradient
  const getCategoryGradient = (category) => {
    const categoryName = category?.name?.toLowerCase() || ''
    
    if (categoryName.includes('fashion') || categoryName.includes('cloth')) {
      return 'linear-gradient(145deg, #8b7355, #d4a574)'
    }
    if (categoryName.includes('food') || categoryName.includes('drink')) {
      return 'linear-gradient(145deg, #d4713d, #f4a261)'
    }
    if (categoryName.includes('tech') || categoryName.includes('gadget') || categoryName.includes('electronic')) {
      return 'linear-gradient(145deg, #264653, #2a9d8f)'
    }
    
    // Default fallback
    return 'linear-gradient(145deg, #222, #85806f)'
  }

  return (
    <div className="product-detail-layout">
      <div className="product-stage">
        <div 
          className="product-media product-media-detail" 
          style={{ background: getCategoryGradient(product.category) }}
        >
          {product.image_url ? <img src={product.image_url} alt={product.name} /> : <span>{product.name.slice(0, 1)}</span>}
        </div>
      </div>
      <div className="product-detail-card">
        <span className="eyebrow">{product.category?.name || 'General'}</span>
        <h1 className="detail-title">{product.name}</h1>
        <p className="detail-copy">{product.ai_description || 'This product will show AI-generated detail copy once available.'}</p>
        <div className="row space-between">
          <strong className="detail-price">${Number(product.price).toFixed(2)}</strong>
          <span className="badge">{product.stock_qty > 0 ? `${product.stock_qty} in stock` : 'Out of stock'}</span>
        </div>
        <div className="row wrap detail-actions">
          <button className="button" onClick={addProduct}>Add to Cart</button>
          <span className="subtle">Checkout keeps your order in this store.</span>
        </div>
      </div>
    </div>
  )
}
