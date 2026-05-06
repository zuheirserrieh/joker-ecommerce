import { Link, Outlet, useParams } from 'react-router-dom'
import { useCart } from '../context/CartContext'

export default function StoreLayout() {
  const { vendorSlug } = useParams()
  const { items } = useCart()

  return (
    <div className="store-shell">
      <header className="store-header">
        <div className="store-nav">
          <Link to={`/${vendorSlug}`} className="store-brand">
            <span className="store-brand-mark">MS</span>
            <div>
              <strong>MarketOS Storefront</strong>
              <div className="subtle">Built for bold product drops and faster checkout.</div>
            </div>
          </Link>
          <div className="row wrap store-nav-links">
            <Link className="pill" to={`/${vendorSlug}`}>Home</Link>
            <Link className="pill" to={`/${vendorSlug}/checkout`}>Cart ({items.length})</Link>
            <Link className="pill" to="/vendor/login">Vendor Login</Link>
          </div>
        </div>
      </header>

      <main className="store-main">
        <Outlet />
      </main>
    </div>
  )
}
