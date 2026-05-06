import { useState } from 'react'
import toast from 'react-hot-toast'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../stores/authStore'

export default function AdminLoginPage() {
  const navigate = useNavigate()
  const { loginAdmin } = useAuthStore()
  const [form, setForm] = useState({ email: 'admin@marketos.test', password: 'password' })
  const [loading, setLoading] = useState(false)

  async function handleSubmit(event) {
    event.preventDefault()
    setLoading(true)

    try {
      await loginAdmin(form)
      toast.success('Signed in as super admin')
      navigate('/admin')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Login failed')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="auth-shell">
      <form className="auth-card" onSubmit={handleSubmit}>
        <span className="eyebrow">Platform Access</span>
        <h1 className="headline">Run every store from one command room.</h1>
        <p className="subtle">Create vendors, monitor platform revenue, and toggle stores without leaving the admin panel.</p>

        <div className="form-grid">
          <label className="field">
            <span>Email</span>
            <input value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} />
          </label>
          <label className="field">
            <span>Password</span>
            <input type="password" value={form.password} onChange={(event) => setForm({ ...form, password: event.target.value })} />
          </label>
          <button className="button" disabled={loading}>{loading ? 'Signing in...' : 'Enter Admin Panel'}</button>
        </div>
      </form>
    </div>
  )
}
