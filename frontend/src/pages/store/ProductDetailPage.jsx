import { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import { api } from '../../lib/api'
import { useCart } from '../../context/CartContext'

export default function ProductDetailPage() {
  const { vendorSlug, productId } = useParams()
  const { addItem } = useCart()
  const [product, setProduct] = useState(null)

  useEffect(() => {
    api.get(`/store/${vendorSlug}/products/${productId}`).then((response) => setProduct(response.data))
  }, [productId, vendorSlug])

  if (!product) return <div className="card">Loading product...</div>

  return (
    <div className="split split-store">
      <div className="card product-stage">
        <div 
          className="product-image product-image-detail" 
          style={{
            background: product.image_url 
              ? `url('${product.image_url}') center/cover no-repeat`
              : '#f0f0f0'
          }}
        >
          {!product.image_url && <span>{product.name.slice(0, 1)}</span>}
        </div>
      </div>
      <div className="card product-detail-card">
        <span className="eyebrow">{product.category?.name || 'General'}</span>
        <h1 className="detail-title">{product.name}</h1>
        <p className="detail-copy">{product.ai_description || 'This product will show AI-generated detail copy once available.'}</p>
        <div className="row space-between">
          <strong className="detail-price">${Number(product.price).toFixed(2)}</strong>
          <span className="badge">{product.stock_qty > 0 ? `${product.stock_qty} in stock` : 'Out of stock'}</span>
        </div>
        <div className="detail-actions">
          <button className="button" onClick={() => addItem(product)}>Add to Cart</button>
        </div>
      </div>
    </div>
  )
}
