import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'
import { useAuthStore } from '../../stores/authStore'

const defaultSettings = {
  // Basic Info
  header_brand: 'MarketOS',
  header_tagline: 'Independent storefront',
  logo_text: 'M',
  store_description: '',
  store_about: '',
  logo_url: '',

  // Contact Information
  contact_phone: '',
  contact_email: '',
  contact_address: '',
  contact_city: '',
  contact_state: '',
  contact_zip: '',
  contact_country: '',

  // Social Media
  social_facebook: '',
  social_instagram: '',
  social_twitter: '',
  social_tiktok: '',
  social_youtube: '',

  // Business Hours (JSON format)
  business_hours: JSON.stringify({
    monday: { open: '09:00', close: '17:00', closed: false },
    tuesday: { open: '09:00', close: '17:00', closed: false },
    wednesday: { open: '09:00', close: '17:00', closed: false },
    thursday: { open: '09:00', close: '17:00', closed: false },
    friday: { open: '09:00', close: '17:00', closed: false },
    saturday: { open: '10:00', close: '16:00', closed: false },
    sunday: { open: '10:00', close: '16:00', closed: true },
  }),

  // Navigation Labels
  nav_shop_label: 'Shop',
  nav_catalog_label: 'Catalog',
  cart_label: 'Cart',

  // Colors
  page_background: '#f8f7f2',
  brand_color: '#1f6f58',

  // Hero
  hero_eyebrow: '',
  hero_headline: '',
  footer_tagline: '',
  hero_cta: 'Shop now',
  secondary_cta: 'Browse categories',

  // Stats
  stat_products_label: 'products',
  stat_categories_label: 'categories',
  stat_stock_label: 'in stock',

  // Featured Products
  featured_label: 'Featured',
  featured_button_label: 'View Details',
  featured_product_id: '',
  featured_product_ids: JSON.stringify([]), // Multiple featured items

  // Categories Section
  categories_eyebrow: 'Collections',
  categories_title: 'Shop by category',
  all_products_label: 'All products',

  // Catalog
  catalog_eyebrow: 'Catalog',
  catalog_title: 'Popular right now',
  catalog_description: '',
  product_details_label: 'Details',
  add_to_cart_label: 'Add to Cart',

  // Shipping & Delivery
  shipping_policy: '',
  shipping_free_threshold: '',
  shipping_cost: '',
  delivery_time: '',

  // Returns & Refunds
  return_policy: '',
  return_days: '30',

  // Payment Methods
  accepted_payments: JSON.stringify(['Credit Card', 'PayPal']),

  // Store Policies
  privacy_policy: '',
  terms_conditions: '',
}

export default function StoreEditorPage() {
  const { user } = useAuthStore()
  const [settings, setSettings] = useState(defaultSettings)
  const [products, setProducts] = useState([])
  const [saving, setSaving] = useState(false)
  const [uploading, setUploading] = useState(false)
  const [activeTab, setActiveTab] = useState('basic')

  useEffect(() => {
    api.get('/vendor/settings').then((response) => setSettings({ ...defaultSettings, ...response.data }))
    api.get('/vendor/products').then((response) => setProducts(response.data.data))
  }, [])

  function update(key, value) {
    setSettings((current) => ({ ...current, [key]: value }))
  }

  async function uploadLogo(file) {
    if (!file) return
    setUploading(true)
    try {
      const formData = new FormData()
      formData.append('image', file)
      const response = await api.post('/vendor/uploads/logo', formData)
      update('logo_url', response.data.url)
      toast.success('Logo uploaded successfully')
    } catch (error) {
      toast.error('Failed to upload logo')
    } finally {
      setUploading(false)
    }
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

        {/* Tabs Navigation */}
        <div className="editor-tabs">
          {[
            { id: 'basic', label: 'Basic Info' },
            { id: 'contact', label: 'Contact & Hours' },
            { id: 'social', label: 'Social Media' },
            { id: 'featured', label: 'Featured Products' },
            { id: 'appearance', label: 'Appearance' },
            { id: 'shipping', label: 'Shipping & Returns' },
            { id: 'policies', label: 'Policies' },
          ].map((tab) => (
            <button
              key={tab.id}
              type="button"
              className={`tab-button ${activeTab === tab.id ? 'active' : ''}`}
              onClick={() => setActiveTab(tab.id)}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {/* Basic Info Tab */}
        {activeTab === 'basic' && (
          <>
            <EditorSection title="Store Logo">
              <div className="logo-upload">
                {settings.logo_url && <img src={settings.logo_url} alt="Store Logo" className="logo-preview" />}
                <label className="upload-button">
                  <input
                    type="file"
                    accept="image/*"
                    onChange={(e) => uploadLogo(e.target.files[0])}
                    disabled={uploading}
                  />
                  {uploading ? 'Uploading...' : 'Upload Logo'}
                </label>
              </div>
            </EditorSection>

            <EditorSection title="Store Information">
              <TextField label="Store name" value={settings.header_brand} onChange={(value) => update('header_brand', value)} />
              <TextField label="Tagline" value={settings.header_tagline} onChange={(value) => update('header_tagline', value)} />
              <TextareaField label="Store description" value={settings.store_description} onChange={(value) => update('store_description', value)} />
              <TextareaField label="About your store" value={settings.store_about} onChange={(value) => update('store_about', value)} />
              <TextField label="Logo text (max 3 chars)" value={settings.logo_text} onChange={(value) => update('logo_text', value.slice(0, 3))} />
            </EditorSection>
          </>
        )}

        {/* Contact & Hours Tab */}
        {activeTab === 'contact' && (
          <>
            <EditorSection title="Contact Information">
              <TextField label="Phone" value={settings.contact_phone} onChange={(value) => update('contact_phone', value)} />
              <TextField label="Email" type="email" value={settings.contact_email} onChange={(value) => update('contact_email', value)} />
              <TextField label="Street Address" value={settings.contact_address} onChange={(value) => update('contact_address', value)} />
              <TextField label="City" value={settings.contact_city} onChange={(value) => update('contact_city', value)} />
              <TextField label="State/Province" value={settings.contact_state} onChange={(value) => update('contact_state', value)} />
              <TextField label="ZIP/Postal Code" value={settings.contact_zip} onChange={(value) => update('contact_zip', value)} />
              <TextField label="Country" value={settings.contact_country} onChange={(value) => update('contact_country', value)} />
            </EditorSection>

            <EditorSection title="Business Hours">
              <BusinessHours value={settings.business_hours} onChange={(value) => update('business_hours', value)} />
            </EditorSection>
          </>
        )}

        {/* Social Media Tab */}
        {activeTab === 'social' && (
          <EditorSection title="Social Media Links">
            <TextField label="Facebook" value={settings.social_facebook} onChange={(value) => update('social_facebook', value)} placeholder="https://facebook.com/..." />
            <TextField label="Instagram" value={settings.social_instagram} onChange={(value) => update('social_instagram', value)} placeholder="https://instagram.com/..." />
            <TextField label="Twitter" value={settings.social_twitter} onChange={(value) => update('social_twitter', value)} placeholder="https://twitter.com/..." />
            <TextField label="TikTok" value={settings.social_tiktok} onChange={(value) => update('social_tiktok', value)} placeholder="https://tiktok.com/..." />
            <TextField label="YouTube" value={settings.social_youtube} onChange={(value) => update('social_youtube', value)} placeholder="https://youtube.com/..." />
          </EditorSection>
        )}

        {/* Featured Products Tab */}
        {activeTab === 'featured' && (
          <>
            <EditorSection title="Primary Featured Product">
              <label className="field">
                <span>Featured product</span>
                <select value={settings.featured_product_id} onChange={(event) => update('featured_product_id', event.target.value)}>
                  <option value="">Select a product</option>
                  {products.map((product) => (
                    <option key={product.id} value={product.id}>{product.name}</option>
                  ))}
                </select>
              </label>
              <TextField label="Featured label" value={settings.featured_label} onChange={(value) => update('featured_label', value)} />
              <TextField label="Featured button text" value={settings.featured_button_label} onChange={(value) => update('featured_button_label', value)} />
            </EditorSection>

            <EditorSection title="Additional Featured Items">
              <p className="helper-text">Select additional products to highlight on your homepage</p>
              <FeaturedProductSelector
                selectedIds={JSON.parse(settings.featured_product_ids || '[]')}
                products={products}
                onChange={(ids) => update('featured_product_ids', JSON.stringify(ids))}
              />
            </EditorSection>
          </>
        )}

        {/* Appearance Tab */}
        {activeTab === 'appearance' && (
          <>
            <EditorSection title="Colors">
              <ColorField label="Brand color" value={settings.brand_color} onChange={(value) => update('brand_color', value)} />
              <ColorField label="Page background" value={settings.page_background} onChange={(value) => update('page_background', value)} />
            </EditorSection>

            <EditorSection title="Navigation">
              <TextField label="Shop nav label" value={settings.nav_shop_label} onChange={(value) => update('nav_shop_label', value)} />
              <TextField label="Catalog nav label" value={settings.nav_catalog_label} onChange={(value) => update('nav_catalog_label', value)} />
              <TextField label="Cart label" value={settings.cart_label} onChange={(value) => update('cart_label', value)} />
            </EditorSection>

            <EditorSection title="Hero Section">
              <TextField label="Eyebrow text" value={settings.hero_eyebrow} onChange={(value) => update('hero_eyebrow', value)} />
              <TextField label="Headline" value={settings.hero_headline} onChange={(value) => update('hero_headline', value)} />
              <TextareaField label="Hero description" value={settings.footer_tagline} onChange={(value) => update('footer_tagline', value)} />
              <TextField label="Primary button" value={settings.hero_cta} onChange={(value) => update('hero_cta', value)} />
              <TextField label="Secondary button" value={settings.secondary_cta} onChange={(value) => update('secondary_cta', value)} />
            </EditorSection>

            <EditorSection title="Stats Section">
              <TextField label="Products label" value={settings.stat_products_label} onChange={(value) => update('stat_products_label', value)} />
              <TextField label="Categories label" value={settings.stat_categories_label} onChange={(value) => update('stat_categories_label', value)} />
              <TextField label="Stock label" value={settings.stat_stock_label} onChange={(value) => update('stat_stock_label', value)} />
            </EditorSection>

            <EditorSection title="Categories Section">
              <TextField label="Category eyebrow" value={settings.categories_eyebrow} onChange={(value) => update('categories_eyebrow', value)} />
              <TextField label="Category title" value={settings.categories_title} onChange={(value) => update('categories_title', value)} />
              <TextField label="All products button" value={settings.all_products_label} onChange={(value) => update('all_products_label', value)} />
            </EditorSection>

            <EditorSection title="Catalog Section">
              <TextField label="Catalog eyebrow" value={settings.catalog_eyebrow} onChange={(value) => update('catalog_eyebrow', value)} />
              <TextField label="Catalog title" value={settings.catalog_title} onChange={(value) => update('catalog_title', value)} />
              <TextareaField label="Catalog description" value={settings.catalog_description} onChange={(value) => update('catalog_description', value)} />
              <TextField label="Details button" value={settings.product_details_label} onChange={(value) => update('product_details_label', value)} />
              <TextField label="Add to cart button" value={settings.add_to_cart_label} onChange={(value) => update('add_to_cart_label', value)} />
            </EditorSection>
          </>
        )}

        {/* Shipping & Returns Tab */}
        {activeTab === 'shipping' && (
          <>
            <EditorSection title="Shipping Information">
              <TextareaField label="Shipping policy" value={settings.shipping_policy} onChange={(value) => update('shipping_policy', value)} />
              <TextField label="Standard shipping cost" value={settings.shipping_cost} onChange={(value) => update('shipping_cost', value)} placeholder="e.g., $9.99" />
              <TextField label="Free shipping threshold" value={settings.shipping_free_threshold} onChange={(value) => update('shipping_free_threshold', value)} placeholder="e.g., $50 or more" />
              <TextField label="Estimated delivery time" value={settings.delivery_time} onChange={(value) => update('delivery_time', value)} placeholder="e.g., 5-7 business days" />
            </EditorSection>

            <EditorSection title="Returns & Refunds">
              <TextareaField label="Return policy" value={settings.return_policy} onChange={(value) => update('return_policy', value)} />
              <TextField label="Return window (days)" value={settings.return_days} onChange={(value) => update('return_days', value)} />
            </EditorSection>

            <EditorSection title="Payment Methods">
              <PaymentMethodSelector
                selectedMethods={JSON.parse(settings.accepted_payments || '[]')}
                onChange={(methods) => update('accepted_payments', JSON.stringify(methods))}
              />
            </EditorSection>
          </>
        )}

        {/* Policies Tab */}
        {activeTab === 'policies' && (
          <>
            <EditorSection title="Store Policies">
              <TextareaField 
                label="Privacy Policy" 
                value={settings.privacy_policy} 
                onChange={(value) => update('privacy_policy', value)}
                rows="6"
              />
              <TextareaField 
                label="Terms & Conditions" 
                value={settings.terms_conditions} 
                onChange={(value) => update('terms_conditions', value)}
                rows="6"
              />
            </EditorSection>
          </>
        )}
      </form>

      <aside className="editor-preview">
        <div className="preview-toolbar">
          <span className="eyebrow">Live Preview</span>
          <Link className="button-secondary" to={`/${user?.slug || 'demo-vendor'}`}>Open Store</Link>
        </div>
        <div className="preview-store" style={{ '--store-accent': settings.brand_color, background: settings.page_background }}>
          <div className="preview-nav">
            {settings.logo_url ? (
              <img src={settings.logo_url} alt="Logo" className="preview-logo" />
            ) : (
              <span className="store-brand-mark">{settings.logo_text || 'M'}</span>
            )}
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
          
          <section className="preview-info">
            {settings.contact_phone && <div><strong>📞 {settings.contact_phone}</strong></div>}
            {settings.contact_email && <div><strong>✉️ {settings.contact_email}</strong></div>}
            {settings.contact_address && <div className="subtle">{settings.contact_address}, {settings.contact_city}</div>}
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

function TextField({ label, value, onChange, type = 'text', placeholder = '' }) {
  return (
    <label className="field">
      <span>{label}</span>
      <input 
        type={type}
        value={value || ''} 
        onChange={(event) => onChange(event.target.value)}
        placeholder={placeholder}
      />
    </label>
  )
}

function TextareaField({ label, value, onChange, rows = 4 }) {
  return (
    <label className="field">
      <span>{label}</span>
      <textarea 
        value={value || ''} 
        onChange={(event) => onChange(event.target.value)} 
        rows={rows}
      />
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

function BusinessHours({ value, onChange }) {
  const [hours, setHours] = useState(() => {
    try {
      return JSON.parse(value || '{}')
    } catch {
      return {}
    }
  })

  const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']

  function updateHours(day, field, val) {
    const updated = {
      ...hours,
      [day]: { ...hours[day], [field]: val }
    }
    setHours(updated)
    onChange(JSON.stringify(updated))
  }

  return (
    <div className="business-hours">
      {days.map((day) => (
        <div key={day} className="hours-row">
          <label className="day-label">{day.charAt(0).toUpperCase() + day.slice(1)}</label>
          <label className="hours-inputs">
            <input
              type="time"
              value={hours[day]?.open || '09:00'}
              onChange={(e) => updateHours(day, 'open', e.target.value)}
              disabled={hours[day]?.closed}
            />
            <span>to</span>
            <input
              type="time"
              value={hours[day]?.close || '17:00'}
              onChange={(e) => updateHours(day, 'close', e.target.value)}
              disabled={hours[day]?.closed}
            />
          </label>
          <label className="closed-checkbox">
            <input
              type="checkbox"
              checked={hours[day]?.closed || false}
              onChange={(e) => updateHours(day, 'closed', e.target.checked)}
            />
            <span>Closed</span>
          </label>
        </div>
      ))}
    </div>
  )
}

function FeaturedProductSelector({ selectedIds, products, onChange }) {
  function toggleProduct(id) {
    const updated = selectedIds.includes(id)
      ? selectedIds.filter((pid) => pid !== id)
      : [...selectedIds, id]
    onChange(updated)
  }

  return (
    <div className="product-grid-selector">
      {products.map((product) => (
        <label key={product.id} className="product-selector-item">
          <input
            type="checkbox"
            checked={selectedIds.includes(product.id)}
            onChange={() => toggleProduct(product.id)}
          />
          <div className="product-info">
            {product.image_url && <img src={product.image_url} alt={product.name} />}
            <span>{product.name}</span>
          </div>
        </label>
      ))}
    </div>
  )
}

function PaymentMethodSelector({ selectedMethods, onChange }) {
  const availableMethods = ['Credit Card', 'Debit Card', 'PayPal', 'Apple Pay', 'Google Pay', 'Bank Transfer', 'Cryptocurrency']

  function toggleMethod(method) {
    const updated = selectedMethods.includes(method)
      ? selectedMethods.filter((m) => m !== method)
      : [...selectedMethods, method]
    onChange(updated)
  }

  return (
    <div className="payment-methods">
      {availableMethods.map((method) => (
        <label key={method} className="payment-method-item">
          <input
            type="checkbox"
            checked={selectedMethods.includes(method)}
            onChange={() => toggleMethod(method)}
          />
          <span>{method}</span>
        </label>
      ))}
    </div>
  )
}
