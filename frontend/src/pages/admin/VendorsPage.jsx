import { useEffect, useState } from 'react'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'

const initialForm = { name: '', market_type: '', email: '', password: 'password123', slug: '' }

export default function VendorsPage() {
  const [vendors, setVendors] = useState([])
  const [form, setForm] = useState(initialForm)

  const load = async () => {
    const { data } = await api.get('/admin/vendors')
    setVendors(data.data)
  }

  useEffect(() => {
    load()
  }, [])

  async function createVendor(event) {
    event.preventDefault()

    try {
      await api.post('/admin/vendors', form)
      toast.success('Vendor created')
      setForm(initialForm)
      load()
    } catch (error) {
      toast.error(error.response?.data?.message || 'Could not create vendor')
    }
  }

  return (
    <div className="split">
      <div className="card">
        <h3>Create Vendor</h3>
        <form className="form-grid" onSubmit={createVendor}>
          <label className="field"><span>Name</span><input value={form.name} onChange={(event) => setForm({ ...form, name: event.target.value })} /></label>
          <label className="field"><span>Slug</span><input value={form.slug} onChange={(event) => setForm({ ...form, slug: event.target.value })} /></label>
          <label className="field"><span>Market Type</span><input value={form.market_type} onChange={(event) => setForm({ ...form, market_type: event.target.value })} /></label>
          <label className="field"><span>Email</span><input value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} /></label>
          <label className="field"><span>Password</span><input value={form.password} onChange={(event) => setForm({ ...form, password: event.target.value })} /></label>
          <button className="button">Create Vendor</button>
        </form>
      </div>

      <div className="card">
        <h3>Vendor Directory</h3>
        <table className="table">
          <thead>
            <tr><th>Name</th><th>Email</th><th>Market</th><th>Status</th></tr>
          </thead>
          <tbody>
            {vendors.map((vendor) => (
              <tr key={vendor.id}>
                <td>{vendor.name}</td>
                <td>{vendor.email}</td>
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
