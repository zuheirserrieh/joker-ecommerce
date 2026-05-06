import { useEffect, useState } from 'react'
import { BarChart, Bar, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from 'recharts'
import { api } from '../../lib/api'

export default function VendorDashboardPage() {
  const [data, setData] = useState(null)

  useEffect(() => {
    api.get('/vendor/dashboard').then((response) => setData(response.data))
  }, [])

  if (!data) return <div className="card">Loading vendor overview...</div>

  return (
    <div className="grid">
      <div className="topbar">
        <div>
          <span className="eyebrow">Vendor Analytics</span>
          <h1 style={{ margin: 0 }}>Command your daily revenue rhythm</h1>
        </div>
      </div>

      <div className="grid cols-4">
        <div className="card metric"><span className="subtle">Monthly Revenue</span><strong>${Number(data.kpis.monthly_revenue).toFixed(2)}</strong></div>
        <div className="card metric"><span className="subtle">Net Profit</span><strong>${Number(data.kpis.net_profit).toFixed(2)}</strong></div>
        <div className="card metric"><span className="subtle">Orders</span><strong>{data.kpis.orders_count}</strong></div>
        <div className="card metric"><span className="subtle">Low Stock</span><strong>{data.kpis.low_stock_count}</strong></div>
      </div>

      <div className="split">
        <div className="card">
          <h3>Daily Revenue</h3>
          <div style={{ height: 280 }}>
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={data.daily_revenue}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="date" />
                <YAxis />
                <Tooltip />
                <Bar dataKey="total" fill="#1e7a5a" radius={[8, 8, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="card">
          <h3>Category Breakdown</h3>
          {data.category_breakdown.length === 0 ? <div className="empty-state">Add categories to see product distribution.</div> : (
            <table className="table">
              <thead>
                <tr><th>Category</th><th>Products</th></tr>
              </thead>
              <tbody>
                {data.category_breakdown.map((item) => (
                  <tr key={item.name}><td>{item.name}</td><td>{item.products_count}</td></tr>
                ))}
              </tbody>
            </table>
          )}
        </div>
      </div>
    </div>
  )
}
