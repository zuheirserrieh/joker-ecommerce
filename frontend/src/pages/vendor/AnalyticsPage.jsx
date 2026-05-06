import { useEffect, useState } from 'react'
import { api } from '../../lib/api'

export default function AnalyticsPage() {
  const [data, setData] = useState(null)

  useEffect(() => {
    api.get('/vendor/dashboard').then((response) => setData(response.data))
  }, [])

  if (!data) return <div className="card">Loading analytics...</div>

  return (
    <div className="grid">
      <div className="card">
        <h3>Top Products</h3>
        <table className="table">
          <thead>
            <tr><th>Name</th><th>Stock</th></tr>
          </thead>
          <tbody>
            {data.top_products.map((product) => (
              <tr key={product.id}><td>{product.name}</td><td>{product.stock_qty}</td></tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
