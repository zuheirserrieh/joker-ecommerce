import { useState } from 'react'
import toast from 'react-hot-toast'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../stores/authStore'

export default function VendorLoginPage() {
  const navigate = useNavigate()
  const { loginVendor } = useAuthStore()
  const [form, setForm] = useState({ email: 'vendor@marketos.test', password: 'password' })
  const [loading, setLoading] = useState(false)

  async function handleSubmit(event) {
    event.preventDefault()
    setLoading(true)

    try {
      await loginVendor(form)
      toast.success('Signed in as vendor')
      navigate('/vendor')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Login failed')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="auth-shell">
      <form className="auth-card" onSubmit={handleSubmit}>
        <span className="eyebrow">Vendor Access</span>
        <h1 className="headline">Shape a storefront that feels like your business.</h1>
        <p className="subtle">Manage products, orders, settings, and AI tools from one scoped dashboard.</p>
        <div className="form-grid">
          <label className="field"><span>Email</span><input value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} /></label>
          <label className="field"><span>Password</span><input type="password" value={form.password} onChange={(event) => setForm({ ...form, password: event.target.value })} /></label>
          <button className="button" disabled={loading}>{loading ? 'Signing in...' : 'Open Vendor Dashboard'}</button>
        </div>
      </form>
    </div>
  )
}
