import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'
import { useAuthStore } from '../../stores/authStore'

const defaultSettings = {
  header_brand: 'MarketOS',
  header_tagline: 'Independent storefront',
  logo_text: 'M',
  nav_shop_label: 'Shop',
  nav_catalog_label: 'Catalog',
  cart_label: 'Cart',
  page_background: '#f8f7f2',
  brand_color: '#1f6f58',
  hero_eyebrow: '',
  hero_headline: '',
  footer_tagline: '',
  hero_cta: 'Shop now',
  secondary_cta: 'Browse categories',
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

export default function StoreEditorPage() {
  const { user } = useAuthStore()
  const [settings, setSettings] = useState(defaultSettings)
  const [products, setProducts] = useState([])
  const [saving, setSaving] = useState(false)

  useEffect(() => {
    api.get('/vendor/settings').then((response) => setSettings({ ...defaultSettings, ...response.data }))
    api.get('/vendor/products').then((response) => setProducts(response.data.data))
  }, [])

  const featuredProduct = products.find((product) => product.id === settings.featured_product_id) || products[0]

  function update(key, value) {
    setSettings((current) => ({ ...current, [key]: value }))
  }

  async function save(event) {
    event.preventDefault()
    setSaving(true)

    try {
      await api.put('/vendor/settings', { settings })
      toast.success('Store settings saved')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Could not save settings')
    } finally {
      setSaving(false)
    }
  }

  return (
    <div className="store-editor-layout">
      <form className="editor-panel" onSubmit={save}>
        <div className="topbar">
          <div>
            <span className="eyebrow">Store Editor</span>
            <h1>Control your public storefront</h1>
          </div>
          <button className="button" disabled={saving}>{saving ? 'Saving...' : 'Save Changes'}</button>
        </div>

        <EditorSection title="Header">
          <TextField label="Store name in nav" value={settings.header_brand} onChange={(value) => update('header_brand', value)} />
          <TextField label="Nav tagline" value={settings.header_tagline} onChange={(value) => update('header_tagline', value)} />
          <TextField label="Logo text" value={settings.logo_text} onChange={(value) => update('logo_text', value.slice(0, 3))} />
          <TextField label="Shop nav label" value={settings.nav_shop_label} onChange={(value) => update('nav_shop_label', value)} />
          <TextField label="Catalog nav label" value={settings.nav_catalog_label} onChange={(value) => update('nav_catalog_label', value)} />
          <TextField label="Cart label" value={settings.cart_label} onChange={(value) => update('cart_label', value)} />
        </EditorSection>

        <EditorSection title="Colors">
          <ColorField label="Brand color" value={settings.brand_color} onChange={(value) => update('brand_color', value)} />
          <ColorField label="Page background" value={settings.page_background} onChange={(value) => update('page_background', value)} />
        </EditorSection>

        <EditorSection title="Hero">
          <TextField label="Eyebrow" value={settings.hero_eyebrow} onChange={(value) => update('hero_eyebrow', value)} />
          <TextField label="Headline" value={settings.hero_headline} onChange={(value) => update('hero_headline', value)} />
          <TextareaField label="Hero paragraph" value={settings.footer_tagline} onChange={(value) => update('footer_tagline', value)} />
          <TextField label="Primary button" value={settings.hero_cta} onChange={(value) => update('hero_cta', value)} />
          <TextField label="Secondary button" value={settings.secondary_cta} onChange={(value) => update('secondary_cta', value)} />
        </EditorSection>

        <EditorSection title="Stats">
          <TextField label="Products label" value={settings.stat_products_label} onChange={(value) => update('stat_products_label', value)} />
          <TextField label="Categories label" value={settings.stat_categories_label} onChange={(value) => update('stat_categories_label', value)} />
          <TextField label="Stock label" value={settings.stat_stock_label} onChange={(value) => update('stat_stock_label', value)} />
        </EditorSection>

        <EditorSection title="Featured Product">
          <label className="field">
            <span>Featured product</span>
            <select value={settings.featured_product_id} onChange={(event) => update('featured_product_id', event.target.value)}>
              <option value="">First product in catalog</option>
              {products.map((product) => (
                <option key={product.id} value={product.id}>{product.name}</option>
              ))}
            </select>
          </label>
          <TextField label="Featured label" value={settings.featured_label} onChange={(value) => update('featured_label', value)} />
          <TextField label="Featured button" value={settings.featured_button_label} onChange={(value) => update('featured_button_label', value)} />
        </EditorSection>

        <EditorSection title="Category Section">
          <TextField label="Category eyebrow" value={settings.categories_eyebrow} onChange={(value) => update('categories_eyebrow', value)} />
          <TextField label="Category title" value={settings.categories_title} onChange={(value) => update('categories_title', value)} />
          <TextField label="All products button" value={settings.all_products_label} onChange={(value) => update('all_products_label', value)} />
        </EditorSection>

        <EditorSection title="Catalog Cards">
          <TextField label="Catalog eyebrow" value={settings.catalog_eyebrow} onChange={(value) => update('catalog_eyebrow', value)} />
          <TextField label="Default catalog title" value={settings.catalog_title} onChange={(value) => update('catalog_title', value)} />
          <TextareaField label="Catalog description" value={settings.catalog_description} onChange={(value) => update('catalog_description', value)} />
          <TextField label="Details button" value={settings.product_details_label} onChange={(value) => update('product_details_label', value)} />
          <TextField label="Add to cart button" value={settings.add_to_cart_label} onChange={(value) => update('add_to_cart_label', value)} />
        </EditorSection>
      </form>

      <aside className="editor-preview">
        <div className="preview-toolbar">
          <span className="eyebrow">Live Preview</span>
          <Link className="button-secondary" to={`/${user?.slug || 'demo-vendor'}`}>Open Store</Link>
        </div>
        <div className="preview-store" style={{ '--store-accent': settings.brand_color, background: settings.page_background }}>
          <div className="preview-nav">
            <span className="store-brand-mark">{settings.logo_text || 'M'}</span>
            <div>
              <strong>{settings.header_brand || user?.name}</strong>
              <div className="subtle">{settings.header_tagline}</div>
            </div>
          </div>
          <section className="preview-hero">
            <span className="eyebrow">{settings.hero_eyebrow || user?.market_type || 'Store'}</span>
            <h2>{settings.hero_headline || user?.name || 'Your storefront headline'}</h2>
            <p>{settings.footer_tagline || 'Your hero paragraph appears here.'}</p>
            <div className="row wrap">
              <span className="button">{settings.hero_cta}</span>
              <span className="button-secondary">{settings.secondary_cta}</span>
            </div>
          </section>
          <section className="preview-featured">
            {featuredProduct?.image_url ? <img src={featuredProduct.image_url} alt="" /> : null}
            <span className="eyebrow">{settings.featured_label}</span>
            <strong>{featuredProduct?.name || 'Featured product'}</strong>
            <span className="button-secondary">{settings.featured_button_label}</span>
          </section>
        </div>
      </aside>
    </div>
  )
}

function EditorSection({ title, children }) {
  return (
    <section className="editor-section">
      <h3>{title}</h3>
      <div className="editor-fields">{children}</div>
    </section>
  )
}

function TextField({ label, value, onChange }) {
  return (
    <label className="field">
      <span>{label}</span>
      <input value={value || ''} onChange={(event) => onChange(event.target.value)} />
    </label>
  )
}

function TextareaField({ label, value, onChange }) {
  return (
    <label className="field">
      <span>{label}</span>
      <textarea value={value || ''} onChange={(event) => onChange(event.target.value)} rows="4" />
    </label>
  )
}

function ColorField({ label, value, onChange }) {
  return (
    <label className="field">
      <span>{label}</span>
      <div className="color-field">
        <input type="color" value={value || '#1f6f58'} onChange={(event) => onChange(event.target.value)} />
        <input value={value || ''} onChange={(event) => onChange(event.target.value)} />
      </div>
    </label>
  )
}
