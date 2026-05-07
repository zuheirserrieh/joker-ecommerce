import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import { useAuthStore } from '../stores/authStore'

export default function AdminLayout() {
  const navigate = useNavigate()
  const { clearAuth, user } = useAuthStore()

  return (
    <div className="dashboard-shell">
      <aside className="sidebar">
        <div className="brand-lockup">
          <span className="brand-mark-small">M</span>
          <span className="eyebrow">MarketOS</span>
          <strong>Super Admin</strong>
          <span className="subtle">{user?.email}</span>
        </div>

        <nav className="nav-list">
          <NavLink to="/admin" end className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Dashboard</NavLink>
          <NavLink to="/admin/vendors" className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}>Vendors</NavLink>
        </nav>

        <div className="sidebar-footer">
          <button
            className="button-secondary"
            onClick={() => {
              clearAuth()
              navigate('/admin/login')
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
