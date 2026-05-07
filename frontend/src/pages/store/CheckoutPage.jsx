import { useState } from 'react'
import toast from 'react-hot-toast'
import { Link, useParams } from 'react-router-dom'
import { api } from '../../lib/api'
import { useCart } from '../../context/useCart'

export default function CheckoutPage() {
  const { vendorSlug } = useParams()
  const { items, updateQuantity, clear } = useCart()
  const [form, setForm] = useState({
    customer: { name: '', email: '', phone: '' },
    payment_method: 'cash',
    notes: '',
  })

  const total = items.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0)

  async function submitOrder(event) {
    event.preventDefault()

    try {
      await api.post(`/store/${vendorSlug}/checkout`, {
        ...form,
        items: items.map((item) => ({ product_id: item.id, quantity: item.quantity })),
      })
      toast.success('Order placed')
      clear()
    } catch (error) {
      toast.error(error.response?.data?.message || 'Checkout failed')
    }
  }

  return (
    <div className="checkout-layout">
      <div className="checkout-card">
        <div className="section-heading">
          <div>
            <span className="eyebrow">Order</span>
            <h3 className="section-title">Your cart</h3>
          </div>
        </div>
        {items.length === 0 ? <div className="empty-state">Your cart is empty.</div> : items.map((item) => (
          <div key={item.id} className="checkout-line">
            <div>
              <strong>{item.name}</strong>
              <div className="subtle">${Number(item.price).toFixed(2)}</div>
            </div>
            <input
              className="qty-input"
              type="number"
              min="0"
              value={item.quantity}
              onChange={(event) => updateQuantity(item.id, Number(event.target.value))}
            />
          </div>
        ))}
        <div className="row space-between checkout-total">
          <strong>Total</strong>
          <strong>${total.toFixed(2)}</strong>
        </div>
      </div>

      <form className="checkout-card form-grid checkout-form" onSubmit={submitOrder}>
        <div className="section-heading">
          <div>
            <span className="eyebrow">Details</span>
            <h3 className="section-title">Checkout</h3>
          </div>
        </div>
        <label className="field"><span>Name</span><input value={form.customer.name} onChange={(event) => setForm({ ...form, customer: { ...form.customer, name: event.target.value } })} /></label>
        <label className="field"><span>Email</span><input value={form.customer.email} onChange={(event) => setForm({ ...form, customer: { ...form.customer, email: event.target.value } })} /></label>
        <label className="field"><span>Phone</span><input value={form.customer.phone} onChange={(event) => setForm({ ...form, customer: { ...form.customer, phone: event.target.value } })} /></label>
        <label className="field">
          <span>Payment Method</span>
          <select value={form.payment_method} onChange={(event) => setForm({ ...form, payment_method: event.target.value })}>
            <option value="cash">Cash on Delivery</option>
            <option value="online">Online Payment</option>
          </select>
        </label>
        {items.length ? (
          <button className="button">Place Order</button>
        ) : (
          <Link className="button" to={`/${vendorSlug}`}>Continue Shopping</Link>
        )}
      </form>
    </div>
  )
}
