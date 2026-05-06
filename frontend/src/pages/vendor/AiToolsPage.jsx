import { useEffect, useState } from 'react'
import { api } from '../../lib/api'

export default function AiToolsPage() {
  const [alerts, setAlerts] = useState([])
  const [forecast, setForecast] = useState([])
  const [earnings, setEarnings] = useState(null)

  useEffect(() => {
    api.get('/vendor/ai/low-stock-alerts').then((response) => setAlerts(response.data.alerts))
    api.get('/vendor/ai/sales-forecast').then((response) => setForecast(response.data.forecast))
    api.get('/vendor/ai/earnings-summary').then((response) => setEarnings(response.data))
  }, [])

  return (
    <div className="grid">
      <div className="grid cols-3">
        <div className="card">
          <h3>Low-Stock Alerts</h3>
          {alerts.length === 0 ? <div className="empty-state">No urgent stock issues.</div> : alerts.map((alert) => (
            <div key={alert.product_id} style={{ marginBottom: 12 }}>
              <strong>{alert.name}</strong>
              <div className="subtle">Stock {alert.stock_qty} / reorder {alert.suggested_reorder_qty} / urgency {alert.urgency}</div>
            </div>
          ))}
        </div>

        <div className="card">
          <h3>7-Day Forecast</h3>
          {forecast.map((entry) => (
            <div key={entry.date} className="row space-between" style={{ marginBottom: 10 }}>
              <span>{entry.date}</span>
              <strong>${Number(entry.projected_revenue).toFixed(2)}</strong>
            </div>
          ))}
        </div>

        <div className="card">
          <h3>Earnings Summary</h3>
          {earnings ? (
            <>
              <p>{earnings.summary}</p>
              <div className="subtle">{earnings.tip}</div>
            </>
          ) : 'Loading...'}
        </div>
      </div>
    </div>
  )
}
