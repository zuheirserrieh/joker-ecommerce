import { useEffect, useState } from 'react'
import { Link, Outlet, useParams } from 'react-router-dom'
import { useCart } from '../context/useCart'
import { api } from '../lib/api'
import StoreFooter from '../components/StoreFooter'

const defaultSettings = {
  header_brand: 'MarketOS',
  header_tagline: 'Independent storefront',
  logo_text: 'M',
  nav_shop_label: 'Shop',
  nav_catalog_label: 'Catalog',
  cart_label: 'Cart',
  brand_color: '#1f6f58',
  page_background: '#f8f7f2',
}

export default function StoreLayout() {
  const { vendorSlug } = useParams()
  const { items } = useCart()
  const [storeShell, setStoreShell] = useState({ vendor: null, settings: defaultSettings })
  const itemCount = items.reduce((total, item) => total + item.quantity, 0)
  const settings = { ...defaultSettings, ...storeShell.settings }

  useEffect(() => {
    let active = true

    api.get(`/store/${vendorSlug}`).then((response) => {
      if (active) {
        setStoreShell(response.data)
      }
    })

    return () => {
      active = false
    }
  }, [vendorSlug])

  return (
    <div className="store-shell" style={{ '--store-accent': settings.brand_color, background: settings.page_background }}>
      <header className="store-header">
        <div className="store-nav">
          <Link to={`/${vendorSlug}`} className="store-brand">
            <span className="store-brand-mark">{settings.logo_text || 'M'}</span>
            <div>
              <strong>{settings.header_brand || storeShell.vendor?.name || 'Storefront'}</strong>
              <div className="subtle">{settings.header_tagline || storeShell.vendor?.market_type || 'Independent storefront'}</div>
            </div>
          </Link>
          <div className="row wrap store-nav-links">
            <Link className="nav-pill" to={`/${vendorSlug}`}>{settings.nav_shop_label}</Link>
            <a className="nav-pill" href={`/${vendorSlug}#products`}>{settings.nav_catalog_label}</a>
            <Link className="cart-button" to={`/${vendorSlug}/checkout`}>
              <span>{settings.cart_label}</span>
              <strong>{itemCount}</strong>
            </Link>
          </div>
        </div>
      </header>

      <main className="store-main">
        <Outlet />
      </main>

      <StoreFooter store={storeShell} settings={storeShell.settings} />
    </div>
  )
}
