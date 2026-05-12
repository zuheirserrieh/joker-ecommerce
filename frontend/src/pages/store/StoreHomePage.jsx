import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'
import { useCart } from '../../context/useCart'

const defaultSettings = {
  brand_color: '#1f6f58',
  hero_eyebrow: '',
  hero_headline: '',
  hero_cta: 'Shop now',
  secondary_cta: 'Browse categories',
  footer_tagline: 'Browse curated products, compare details, and check out in a few steps.',
  stat_products_label: 'products',
  stat_categories_label: 'categories',
  stat_stock_label: 'in stock',
  featured_label: 'Featured',
  featured_button_label: 'View Details',
  featured_product_id: '',
  categories_eyebrow: 'Collections',
  categories_title: 'Shop by category',
  all_products_label: 'All products',
  catalog_eyebrow: 'Catalog',
  catalog_title: 'Popular right now',
  catalog_description: '',
  product_details_label: 'Details',
  add_to_cart_label: 'Add to Cart',
}

export default function StoreHomePage() {
  const { vendorSlug } = useParams()
  const { addItem } = useCart()
  const [store, setStore] = useState(null)
  const [products, setProducts] = useState([])
  const [selectedCategory, setSelectedCategory] = useState('all')

  useEffect(() => {
    api.get(`/store/${vendorSlug}`).then((response) => setStore(response.data))
    api.get(`/store/${vendorSlug}/products`).then((response) => setProducts(response.data.data))
  }, [vendorSlug])

  if (!store) return <div className="card">Loading storefront...</div>

  const settings = { ...defaultSettings, ...store.settings }
  const brandColor = settings.brand_color
  const heroHeadline = settings.hero_headline || store.vendor.name
  const heroCta = settings.hero_cta
  const footerTagline = settings.footer_tagline
  const featuredProduct = products.find((product) => product.id === settings.featured_product_id) || products[0]
  const visibleProducts = selectedCategory === 'all'
    ? products
    : products.filter((product) => product.category?.id === selectedCategory)
  const totalStock = products.reduce((sum, product) => sum + Number(product.stock_qty || 0), 0)
  const selectedCategoryName = selectedCategory === 'all'
    ? settings.catalog_title
    : store.categories.find((category) => category.id === selectedCategory)?.name || 'Products'

  function selectCategory(categoryId) {
    setSelectedCategory(categoryId)
    window.requestAnimationFrame(() => {
      document.getElementById('products')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
    })
  }

  const addProduct = (product) => {
    addItem(product)
    toast.success(`${product.name} added to cart`)
  }

  return (
    <>
      <section className="store-hero" style={{ '--store-accent': brandColor }}>
        <div className="hero-copy">
          <span className="eyebrow">{settings.hero_eyebrow || store.vendor.market_type}</span>
          <h1 className="hero-headline">{heroHeadline}</h1>
          <p className="hero-text">{footerTagline}</p>
          <div className="row wrap hero-actions">
            <a className="button" href="#products">{heroCta}</a>
            <a className="button-secondary" href="#categories">{settings.secondary_cta}</a>
          </div>
          <div className="store-stats">
            <span><strong>{products.length}</strong> {settings.stat_products_label}</span>
            <span><strong>{store.categories.length}</strong> {settings.stat_categories_label}</span>
            <span><strong>{totalStock}</strong> {settings.stat_stock_label}</span>
          </div>
        </div>

        <div className="hero-showcase" aria-label="Featured products">
          <div className="showcase-card showcase-card-large">
            <ProductMedia product={featuredProduct} brandColor={brandColor} />
            <div className="showcase-copy">
              <span className="eyebrow">{settings.featured_label}</span>
              <strong>{featuredProduct?.name || 'Featured product'}</strong>
              <p>{featuredProduct?.ai_description || 'Your featured product appears here when the catalog loads.'}</p>
              {featuredProduct ? (
                <Link className="button-secondary" to={`/${vendorSlug}/products/${featuredProduct.id}`}>{settings.featured_button_label}</Link>
              ) : null}
            </div>
          </div>
        </div>
      </section>

      <section id="categories" className="store-section category-band">
        <div className="section-heading">
          <div>
            <span className="eyebrow">{settings.categories_eyebrow}</span>
            <h2 className="section-title">{settings.categories_title}</h2>
          </div>
        </div>
        <div className="category-row">
          <button
            className={`category-tile ${selectedCategory === 'all' ? 'active' : ''}`}
            onClick={() => selectCategory('all')}
            type="button"
          >
            {settings.all_products_label}
          </button>
          {store.categories.map((category) => (
            <button
              key={category.id}
              className={`category-tile ${selectedCategory === category.id ? 'active' : ''}`}
              onClick={() => selectCategory(category.id)}
              type="button"
            >
              {category.name}
            </button>
          ))}
        </div>
      </section>

      <section id="products" className="store-section">
        <div className="section-heading">
          <div>
            <span className="eyebrow">{settings.catalog_eyebrow}</span>
            <h2 className="section-title">{selectedCategoryName}</h2>
          </div>
          <p className="subtle section-copy">{settings.catalog_description || `${visibleProducts.length} products available in this view.`}</p>
        </div>

        <div className="products-grid">
        {visibleProducts.map((product) => (
          <article key={product.id} className="product-card">
            <ProductMedia product={product} brandColor={brandColor} />
            <div className="row space-between">
              <span className="product-category">{product.category?.name || 'General'}</span>
              <span className="badge">${Number(product.price).toFixed(2)}</span>
            </div>
            <strong className="product-name">{product.name}</strong>
            <p className="subtle">{product.ai_description || 'AI-crafted product copy appears here once descriptions are generated.'}</p>
            <div className="row wrap">
              <Link className="button-secondary" to={`/${vendorSlug}/products/${product.id}`}>{settings.product_details_label}</Link>
              <button className="button" onClick={() => addProduct(product)}>{settings.add_to_cart_label}</button>
            </div>
          </article>
        ))}
        </div>
      </section>
    </>
  )
}

function ProductMedia({ product, brandColor }) {
  const [failed, setFailed] = useState(false)
  const imageUrl = failed ? null : product?.image_url
  
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
    return `linear-gradient(145deg, ${brandColor}, #f5c15f)`
  }
  
  const gradient = getCategoryGradient(product?.category)

  return (
    <div className="product-media" style={{ background: gradient }}>
      {imageUrl ? <img src={imageUrl} alt={product.name} onError={() => setFailed(true)} /> : null}
      {!imageUrl && <span>{product?.name?.slice(0, 1) || 'M'}</span>}
    </div>
  )
}
