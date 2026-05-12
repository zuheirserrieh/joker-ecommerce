export default function StoreFooter({ store, settings }) {
  if (!store || !store.vendor) return null

  const vendor = store.vendor
  const brandColor = settings?.brand_color || '#1f6f58'

  return (
    <footer className="store-footer" style={{ '--store-accent': brandColor }}>
      <div className="footer-content">
        <div className="footer-section">
          <h4>{vendor.name}</h4>
          <p className="subtle">{vendor.market_type}</p>
          {settings?.footer_tagline && <p className="subtle">{settings.footer_tagline}</p>}
        </div>

        <div className="footer-section">
          <h4>Contact Us</h4>
          <div className="contact-info">
            {vendor.email && (
              <div className="contact-item">
                <span className="contact-label">Email</span>
                <a href={`mailto:${vendor.email}`}>{vendor.email}</a>
              </div>
            )}
            {vendor.phone && (
              <div className="contact-item">
                <span className="contact-label">Phone</span>
                <a href={`tel:${vendor.phone}`}>{vendor.phone}</a>
              </div>
            )}
          </div>
        </div>

        <div className="footer-section">
          <h4>Support</h4>
          <ul className="footer-links">
            <li><a href="#products">Browse Products</a></li>
            <li><a href="#categories">Shop by Category</a></li>
          </ul>
        </div>
      </div>

      <div className="footer-bottom">
        <p className="subtle">&copy; {new Date().getFullYear()} {vendor.name}. All rights reserved.</p>
      </div>
    </footer>
  )
}
