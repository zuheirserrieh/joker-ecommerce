import { useEffect, useState } from 'react'
import { api } from '../../lib/api'

export default function OrdersPage() {
  const [orders, setOrders] = useState([])

  useEffect(() => {
    api.get('/vendor/orders').then((response) => setOrders(response.data.data))
  }, [])

  return (
    <div className="card">
      <h3>Orders</h3>
      {orders.length === 0 ? <div className="empty-state">No orders yet.</div> : (
        <table className="table">
          <thead>
            <tr><th>ID</th><th>Status</th><th>Payment</th><th>Total</th><th>Profit</th></tr>
          </thead>
          <tbody>
            {orders.map((order) => (
              <tr key={order.id}>
                <td>{order.id.slice(0, 8)}...</td>
                <td>{order.status}</td>
                <td>{order.payment_status}</td>
                <td>${Number(order.total_amount).toFixed(2)}</td>
                <td>${Number(order.profit_amount).toFixed(2)}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}
