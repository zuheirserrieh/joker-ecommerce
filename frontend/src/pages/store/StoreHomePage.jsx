import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { api } from '../../lib/api'
import { useCart } from '../../context/CartContext'

export default function StoreHomePage() {
  const { vendorSlug } = useParams()
  const { addItem } = useCart()
  const [store, setStore] = useState(null)
  const [products, setProducts] = useState([])

  useEffect(() => {
    api.get(`/store/${vendorSlug}`).then((response) => setStore(response.data))
    api.get(`/store/${vendorSlug}/products`).then((response) => setProducts(response.data.data))
  }, [vendorSlug])

  if (!store) return <div className="card">Loading storefront...</div>

  const brandColor = store.settings.brand_color || '#1e7a5a'
  const heroHeadline = store.settings.hero_headline || store.vendor.name
  const footerTagline = store.settings.footer_tagline || 'Browse dynamic products, filter by category, and check out in a few steps.'
  const featuredProduct = products[0]

  return (
    <>
      <section className="hero hero-grid" style={{ background: `linear-gradient(135deg, ${brandColor}, #f3b34d)` }}>
        <div className="hero-copy">
          <span className="eyebrow hero-eyebrow">{store.vendor.market_type}</span>
          <h1 className="headline hero-headline">{heroHeadline}</h1>
          <p className="hero-text">{footerTagline}</p>
          <div className="row wrap hero-actions">
            <a className="button button-light" href="#products">Shop collection</a>
            <span className="hero-note">{products.length} products live now</span>
          </div>
        </div>

        <div className="hero-panel">
          <div className="hero-panel-label">Store snapshot</div>
          <div className="hero-stat-grid">
            <div className="hero-stat">
              <strong>{products.length}</strong>
              <span>Products</span>
            </div>
            <div className="hero-stat">
              <strong>{store.categories.length}</strong>
              <span>Categories</span>
            </div>
            <div className="hero-stat">
              <strong>24h</strong>
              <span>Fast checkout</span>
            </div>
          </div>
          <div className="hero-feature">
            <span className="eyebrow">Featured pick</span>
            <strong>{featuredProduct?.name || 'Fresh arrivals loading'}</strong>
            <p>{featuredProduct?.ai_description || 'Handpicked products, polished presentation, and a storefront built to convert.'}</p>
          </div>
        </div>
      </section>

      <section className="card section-card">
        <div className="section-heading">
          <div>
            <span className="eyebrow">Collections</span>
            <h2 className="section-title">Shop by category</h2>
          </div>
        </div>
        <div className="pill-row">
          {store.categories.map((category) => (
            <span key={category.id} className="pill">{category.name}</span>
          ))}
        </div>
      </section>

      <section id="products" className="store-section">
        <div className="section-heading">
          <div>
            <span className="eyebrow">Catalog</span>
            <h2 className="section-title">Featured products</h2>
          </div>
          <p className="subtle section-copy">Large visuals, faster scanning, and product cards that give the store some presence.</p>
        </div>

        <div className="products-grid">
        {products.map((product) => (
          <article key={product.id} className="card product-card">
            <div className="product-image product-image-store" style={{ 
              background: product.image_url 
                ? `url('${product.image_url}') center/cover no-repeat`
                : `linear-gradient(145deg, color-mix(in srgb, ${brandColor} 78%, white), rgba(243, 179, 77, 0.82))`
            }}>
              {!product.image_url && <span>{product.name.slice(0, 1)}</span>}
            </div>
            <span className="eyebrow">{product.category?.name || 'General'}</span>
            <div className="row space-between">
              <strong>{product.name}</strong>
              <span className="badge">${Number(product.price).toFixed(2)}</span>
            </div>
            <p className="subtle">{product.ai_description || 'AI-crafted product copy appears here once descriptions are generated.'}</p>
            <div className="row wrap">
              <Link className="button-secondary" to={`/${vendorSlug}/products/${product.id}`}>View</Link>
              <button className="button" onClick={() => addItem(product)}>Add to Cart</button>
            </div>
          </article>
        ))}
        </div>
      </section>
    </>
  )
}
