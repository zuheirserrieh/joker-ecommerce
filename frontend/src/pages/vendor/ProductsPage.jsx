import { useEffect, useState } from 'react'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'

const initialProduct = { name: '', price: '', cost_price: '', stock_qty: '', low_stock_threshold: 5, image_url: '' }

export default function ProductsPage() {
  const [products, setProducts] = useState([])
  const [form, setForm] = useState(initialProduct)
  const [imagePreview, setImagePreview] = useState('')
  const [uploading, setUploading] = useState(false)
  const [editingProductId, setEditingProductId] = useState(null)

  async function load() {
    const { data } = await api.get('/vendor/products')
    setProducts(data.data)
  }

  useEffect(() => {
    let active = true

    async function loadProducts() {
      const { data } = await api.get('/vendor/products')

      if (active) {
        setProducts(data.data)
      }
    }

    loadProducts()

    return () => {
      active = false
    }
  }, [])

  async function handleImageUpload(event) {
    const file = event.target.files?.[0]
    if (!file) return

    setUploading(true)
    try {
      const formData = new FormData()
      formData.append('image', file)
      
      const { data } = await api.post('/vendor/uploads/product-image', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      
      setForm({ ...form, image_url: data.url })
      setImagePreview(data.url)
      toast.success('Image uploaded successfully')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Could not upload image')
    } finally {
      setUploading(false)
    }
  }

  async function createProduct(event) {
    event.preventDefault()

    try {
      if (editingProductId) {
        await api.patch(`/vendor/products/${editingProductId}`, form)
        toast.success('Product updated')
      } else {
        await api.post('/vendor/products', form)
        toast.success('Product created')
      }
      setForm(initialProduct)
      setImagePreview('')
      setEditingProductId(null)
      load()
    } catch (error) {
      toast.error(error.response?.data?.message || 'Could not save product')
    }
  }

  function editProduct(product) {
    setEditingProductId(product.id)
    setForm({
      name: product.name || '',
      price: product.price || '',
      cost_price: product.cost_price || '',
      stock_qty: product.stock_qty || '',
      low_stock_threshold: product.low_stock_threshold || 5,
      image_url: product.image_url || '',
      ai_description: product.ai_description || '',
      category_id: product.category_id || '',
    })
    setImagePreview(product.image_url || '')
  }

  function cancelEdit() {
    setEditingProductId(null)
    setForm(initialProduct)
    setImagePreview('')
  }

  return (
    <div className="split">
      <div className="card">
        <div className="row space-between">
          <h3>{editingProductId ? 'Edit Product' : 'Add Product'}</h3>
          {editingProductId ? <button className="button-secondary" type="button" onClick={cancelEdit}>Cancel</button> : null}
        </div>
        <form className="form-grid" onSubmit={createProduct}>
          <label className="field"><span>Product Photo</span>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
              {imagePreview && (
                <div style={{ 
                  width: '100%', 
                  maxWidth: '200px', 
                  height: '200px', 
                  background: '#f0f0f0', 
                  borderRadius: '8px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  overflow: 'hidden'
                }}>
                  <img src={imagePreview} alt="preview" style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'cover' }} />
                </div>
              )}
              <input 
                type="file" 
                accept="image/*" 
                onChange={handleImageUpload}
                disabled={uploading}
                style={{ cursor: uploading ? 'not-allowed' : 'pointer' }}
              />
              {uploading && <span style={{ fontSize: '12px', color: '#666' }}>Uploading...</span>}
            </div>
          </label>
          <label className="field"><span>Name</span><input value={form.name} onChange={(event) => setForm({ ...form, name: event.target.value })} required /></label>
          <label className="field"><span>Price</span><input type="number" value={form.price} onChange={(event) => setForm({ ...form, price: event.target.value })} required /></label>
          <label className="field"><span>Cost Price</span><input type="number" value={form.cost_price} onChange={(event) => setForm({ ...form, cost_price: event.target.value })} /></label>
          <label className="field"><span>Stock Qty</span><input type="number" value={form.stock_qty} onChange={(event) => setForm({ ...form, stock_qty: event.target.value })} /></label>
          <label className="field"><span>Photo URL</span><input value={form.image_url} onChange={(event) => {
            setForm({ ...form, image_url: event.target.value })
            setImagePreview(event.target.value)
          }} /></label>
          <label className="field"><span>AI Comment</span><textarea value={form.ai_description || ''} onChange={(event) => setForm({ ...form, ai_description: event.target.value })} rows="4" /></label>
          <button className="button">{editingProductId ? 'Update Product' : 'Save Product'}</button>
        </form>
      </div>

      <div className="card">
        <h3>Catalog</h3>
        <table className="table">
          <thead>
            <tr><th>Photo</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th>Action</th></tr>
          </thead>
          <tbody>
            {products.map((product) => (
              <tr key={product.id}>
                <td style={{ width: '60px' }}>
                  {product.image_url ? (
                    <div style={{
                      width: '50px',
                      height: '50px',
                      borderRadius: '4px',
                      overflow: 'hidden',
                      background: '#f0f0f0',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center'
                    }}>
                      <img src={product.image_url} alt={product.name} style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'cover' }} />
                    </div>
                  ) : (
                    <span style={{ fontSize: '12px', color: '#ccc' }}>No image</span>
                  )}
                </td>
                <td>{product.name}</td>
                <td>${Number(product.price).toFixed(2)}</td>
                <td>{product.stock_qty}</td>
                <td>{product.is_active ? 'Active' : 'Hidden'}</td>
                <td><button className="button-secondary" type="button" onClick={() => editProduct(product)}>Edit</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}
