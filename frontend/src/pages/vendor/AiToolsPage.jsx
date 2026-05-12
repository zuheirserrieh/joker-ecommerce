import { useEffect, useState } from 'react'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'

export default function AiToolsPage() {
  const [alerts, setAlerts] = useState([])
  const [forecast, setForecast] = useState([])
  const [earnings, setEarnings] = useState(null)
  const [insights, setInsights] = useState(null)
  const [pricing, setPricing] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  const [descriptionLoading, setDescriptionLoading] = useState(false)
  const [descriptionForm, setDescriptionForm] = useState({
    name: '',
    category: '',
    price: '',
    market_type: 'general',
  })
  const [generatedDescription, setGeneratedDescription] = useState(null)

  useEffect(() => {
    loadAllData()
  }, [])

  async function loadAllData() {
    setLoading(true)
    setError(null)
    try {
      const [alertsRes, forecastRes, earningsRes, insightsRes, pricingRes] = await Promise.all([
        api.get('/vendor/ai/low-stock-alerts'),
        api.get('/vendor/ai/sales-forecast'),
        api.get('/vendor/ai/earnings-summary'),
        api.get('/vendor/ai/customer-insights'),
        api.get('/vendor/ai/pricing-recommendation'),
      ])

      setAlerts(alertsRes.data.alerts || [])
      setForecast(forecastRes.data || {})
      setEarnings(earningsRes.data || {})
      setInsights(insightsRes.data || {})
      setPricing(pricingRes.data.recommendations || [])
    } catch (err) {
      setError('Failed to load AI insights. Please try again.')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  async function generateDescription() {
    if (!descriptionForm.name.trim()) {
      toast.error('Please enter a product name')
      return
    }

    setDescriptionLoading(true)
    try {
      const response = await api.post('/vendor/ai/product-description', descriptionForm)
      setGeneratedDescription(response.data)
      toast.success(response.data.ai_generated ? '✨ AI description generated!' : 'Description created')
    } catch (err) {
      toast.error('Failed to generate description')
      console.error(err)
    } finally {
      setDescriptionLoading(false)
    }
  }

  function copyDescription() {
    if (generatedDescription?.description) {
      navigator.clipboard.writeText(generatedDescription.description)
      toast.success('Copied to clipboard!')
    }
  }

  function resetForm() {
    setDescriptionForm({ name: '', category: '', price: '', market_type: 'general' })
    setGeneratedDescription(null)
  }

  if (loading) {
    return <div className="grid" style={{ padding: 40, textAlign: 'center' }}>Loading AI insights...</div>
  }

  return (
    <div className="ai-tools-layout">
      {error && <div className="error-banner">{error}</div>}

      {/* Key Metrics */}
      <div className="grid cols-4">
        {earnings && (
          <>
            <MetricCard
              title="Total Revenue"
              value={`$${earnings.gross_revenue?.toFixed(2) || 0}`}
              subtitle={`Profit: $${earnings.gross_profit?.toFixed(2) || 0}`}
              icon="💰"
            />
            <MetricCard
              title="Profit Margin"
              value={`${earnings.margin || 0}%`}
              subtitle={`${earnings.total_orders || 0} total orders`}
              icon="📊"
            />
            <MetricCard
              title="Avg Order Value"
              value={`$${earnings.avg_order_value?.toFixed(2) || 0}`}
              subtitle={`30-day revenue: $${earnings.last30_revenue?.toFixed(2) || 0}`}
              icon="🛒"
            />
            {insights && (
              <MetricCard
                title="Repeat Customers"
                value={`${insights.repeat_rate || 0}%`}
                subtitle={`${insights.repeat_customers || 0} of ${insights.total_customers || 0}`}
                icon="⭐"
              />
            )}
          </>
        )}
      </div>

      {/* Main Grid */}
      <div className="grid cols-3" style={{ marginTop: 20 }}>
        {/* Low Stock Alerts */}
        <div className="card">
          <h3>⚠️ Low-Stock Alerts</h3>
          {alerts.length === 0 ? (
            <div className="empty-state">All inventory levels look good!</div>
          ) : (
            <div className="alert-list">
              {alerts.map((alert) => (
                <div key={alert.product_id} className="alert-item" style={{ marginBottom: 12 }}>
                  {alert.image_url && <img src={alert.image_url} alt={alert.name} className="alert-thumb" />}
                  <div style={{ flex: 1 }}>
                    <strong>{alert.name}</strong>
                    <div className="subtle" style={{ fontSize: 12 }}>
                      Current: {alert.stock_qty} • Threshold: {alert.low_stock_threshold}
                    </div>
                    <div style={{ fontSize: 12, marginTop: 4 }}>
                      <span className={`badge urgent-${alert.urgency}`}>🔴 {alert.urgency.toUpperCase()}</span>
                      <span style={{ marginLeft: 8, color: '#6c6a63' }}>Reorder: {alert.suggested_reorder_qty}</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Sales Forecast */}
        <div className="card">
          <h3>📈 Sales Forecast</h3>
          <div className="metric-box">
            <div className="metric-row">
              <span>7-Day Average</span>
              <strong>${forecast.last7_avg?.toFixed(2) || 0}</strong>
            </div>
            <div className="metric-row">
              <span>30-Day Average</span>
              <strong>${forecast.last30_avg?.toFixed(2) || 0}</strong>
            </div>
            <div className="metric-row trend-up">
              <span>{forecast.trend === 'up' ? '📈 Trending Up' : '📊 Stable'}</span>
              <strong>{forecast.insight}</strong>
            </div>
          </div>
          {forecast.forecast?.length > 0 && (
            <div className="forecast-preview">
              <strong style={{ fontSize: 12, color: '#6c6a63' }}>14-Day Forecast:</strong>
              <div style={{ marginTop: 8, display: 'grid', gap: 4 }}>
                {forecast.forecast?.slice(0, 5).map((f) => (
                  <div key={f.date} style={{ fontSize: 12, display: 'flex', justifyContent: 'space-between' }}>
                    <span>{f.date}</span>
                    <span>${f.projected_revenue}</span>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Earnings Summary */}
        <div className="card">
          <h3>💡 Earnings Summary</h3>
          {earnings && (
            <>
              <div className="metric-box">
                <div className="metric-row">
                  <span>Gross Revenue</span>
                  <strong>${earnings.gross_revenue?.toFixed(2) || 0}</strong>
                </div>
                <div className="metric-row">
                  <span>Total Cost</span>
                  <strong>${earnings.total_cost?.toFixed(2) || 0}</strong>
                </div>
                <div className="metric-row highlight">
                  <span>Gross Profit</span>
                  <strong>${earnings.gross_profit?.toFixed(2) || 0}</strong>
                </div>
                <div className="metric-row">
                  <span>Profit Margin</span>
                  <strong>{earnings.margin || 0}%</strong>
                </div>
              </div>
              <div className="tip-box">{earnings.tip}</div>
            </>
          )}
        </div>
      </div>

      {/* Customer Insights & Pricing */}
      <div className="grid cols-2" style={{ marginTop: 20 }}>
        {/* Customer Insights */}
        <div className="card">
          <h3>👥 Customer Insights</h3>
          {insights && (
            <>
              <div className="metric-box">
                <div className="metric-row">
                  <span>Total Customers</span>
                  <strong>{insights.total_customers || 0}</strong>
                </div>
                <div className="metric-row">
                  <span>Repeat Customers</span>
                  <strong>{insights.repeat_customers || 0}</strong>
                </div>
                <div className="metric-row">
                  <span>New Customers</span>
                  <strong>{insights.new_customers || 0}</strong>
                </div>
                <div className="metric-row highlight">
                  <span>Repeat Rate</span>
                  <strong>{insights.repeat_rate || 0}%</strong>
                </div>
                <div className="metric-row">
                  <span>Avg Orders/Customer</span>
                  <strong>{insights.avg_orders_per_customer || 0}</strong>
                </div>
              </div>
              <div className="tip-box">{insights.insight}</div>
            </>
          )}
        </div>

        {/* Pricing Recommendations */}
        <div className="card">
          <h3>💹 Pricing Recommendations</h3>
          {pricing.length === 0 ? (
            <div className="empty-state">No products yet. Add products to get pricing insights.</div>
          ) : (
            <div className="pricing-list">
              {pricing.slice(0, 8).map((item) => (
                <div key={item.product_id} className="pricing-item">
                  <div>
                    <strong style={{ fontSize: 13 }}>{item.name}</strong>
                    <div className="subtle" style={{ fontSize: 11 }}>
                      Margin: {item.current_margin}% • Sold: {item.orders_sold}
                    </div>
                  </div>
                  <div style={{ textAlign: 'right', fontSize: 12 }}>
                    <div>${item.current_price.toFixed(2)} → ${item.recommended_price.toFixed(2)}</div>
                    <div className="recommendation-badge">{item.recommendation}</div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* AI Description Generator */}
      <div className="card" style={{ marginTop: 20 }}>
        <h3>✨ AI Product Description Generator</h3>
        <p className="subtle" style={{ marginBottom: 12 }}>
          Generate compelling product descriptions instantly using AI
        </p>

        <div className="generator-form">
          <div className="form-row">
            <input
              type="text"
              placeholder="Product name *"
              value={descriptionForm.name}
              onChange={(e) => setDescriptionForm({ ...descriptionForm, name: e.target.value })}
            />
            <input
              type="text"
              placeholder="Category (e.g., Electronics)"
              value={descriptionForm.category}
              onChange={(e) => setDescriptionForm({ ...descriptionForm, category: e.target.value })}
            />
            <input
              type="number"
              placeholder="Price ($)"
              value={descriptionForm.price}
              onChange={(e) => setDescriptionForm({ ...descriptionForm, price: e.target.value })}
            />
            <select
              value={descriptionForm.market_type}
              onChange={(e) => setDescriptionForm({ ...descriptionForm, market_type: e.target.value })}
            >
              <option value="general">General Market</option>
              <option value="luxury">Luxury</option>
              <option value="budget">Budget</option>
              <option value="professional">Professional</option>
              <option value="casual">Casual</option>
              <option value="eco-friendly">Eco-friendly</option>
            </select>
            <button
              className="button"
              onClick={generateDescription}
              disabled={descriptionLoading}
              style={{ flex: 0 }}
            >
              {descriptionLoading ? 'Generating...' : 'Generate'}
            </button>
          </div>

          {generatedDescription && (
            <div className="description-result">
              <div className="result-header">
                <span>{generatedDescription.ai_generated ? '✨ AI Generated' : '📝 Suggestion'}</span>
                <div className="result-actions">
                  <button className="button-secondary" onClick={copyDescription}>
                    📋 Copy
                  </button>
                  <button className="button-secondary" onClick={resetForm}>
                    🔄 Clear
                  </button>
                </div>
              </div>
              <p>{generatedDescription.description}</p>
            </div>
          )}
        </div>
      </div>

      <div style={{ marginTop: 12, textAlign: 'center' }}>
        <button className="button-secondary" onClick={loadAllData}>
          🔄 Refresh All Data
        </button>
      </div>
    </div>
  )
}

function MetricCard({ title, value, subtitle, icon }) {
  return (
    <div className="metric-card">
      <span className="metric-icon">{icon}</span>
      <h4>{title}</h4>
      <div className="metric-value">{value}</div>
      {subtitle && <div className="metric-subtitle">{subtitle}</div>}
    </div>
  )
}
