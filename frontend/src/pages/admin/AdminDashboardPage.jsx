import { useEffect, useState } from 'react'
import { api } from '../../lib/api'

export default function AdminDashboardPage() {
  const [data, setData] = useState(null)

  useEffect(() => {
    api.get('/admin/dashboard').then((response) => setData(response.data))
  }, [])

  if (!data) return <div className="card">Loading dashboard...</div>

  return (
    <div className="grid">
      <div className="topbar">
        <div>
          <span className="eyebrow">Platform Snapshot</span>
          <h1 style={{ margin: 0 }}>MarketOS control center</h1>
        </div>
      </div>

      <div className="grid cols-4">
        <div className="card metric"><span className="subtle">Vendors</span><strong>{data.vendors_count}</strong></div>
        <div className="card metric"><span className="subtle">Active Vendors</span><strong>{data.active_vendors_count}</strong></div>
        <div className="card metric"><span className="subtle">Revenue</span><strong>${Number(data.platform_revenue).toFixed(2)}</strong></div>
        <div className="card metric"><span className="subtle">Profit</span><strong>${Number(data.platform_profit).toFixed(2)}</strong></div>
      </div>

      <div className="card">
        <h3>Recent Vendors</h3>
        <table className="table">
          <thead>
            <tr><th>Name</th><th>Slug</th><th>Market</th><th>Status</th></tr>
          </thead>
          <tbody>
            {data.recent_vendors.map((vendor) => (
              <tr key={vendor.id}>
                <td>{vendor.name}</td>
                <td>{vendor.slug}</td>
                <td>{vendor.market_type}</td>
                <td>{vendor.is_active ? 'Active' : 'Paused'}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
