import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import { useAuthStore } from '../stores/authStore'

export default function VendorLayout() {
  const navigate = useNavigate()
  const { clearAuth, user } = useAuthStore()

  return (
    <div className="dashboard-shell">
      <aside className="sidebar">
        <div className="brand-lockup">
          <span className="brand-mark-small">M</span>
          <span className="eyebrow">{user?.market_type || 'General Market'}</span>
          <strong>{user?.name || 'Vendor Hub'}</strong>
          <span className="subtle">{user?.email}</span>
        </div>

        <nav className="nav-list">
          <NavLink to="/vendor" end className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Overview</NavLink>
          <NavLink to="/vendor/products" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Products</NavLink>
          <NavLink to="/vendor/orders" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Orders</NavLink>
          <NavLink to="/vendor/store-editor" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Store Editor</NavLink>
          <NavLink to="/vendor/ai-tools" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>AI Tools</NavLink>
          <NavLink to="/vendor/analytics" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Analytics</NavLink>
          {user?.slug ? <NavLink to={`/${user.slug}`} className="nav-link">Public Store</NavLink> : null}
        </nav>

        <div className="sidebar-footer">
          <button
            className="button-secondary"
            onClick={() => {
              clearAuth()
              navigate('/vendor/login')
            }}
          >
            Log out
          </button>
        </div>
      </aside>

      <main className="content">
        <Outlet />
      </main>
    </div>
  )
}
