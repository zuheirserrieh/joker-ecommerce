import { useEffect, useState } from 'react'
import toast from 'react-hot-toast'
import { api } from '../../lib/api'

export default function StoreEditorPage() {
  const [settings, setSettings] = useState({
    hero_headline: '',
    hero_cta: '',
    brand_color: '#1e7a5a',
    footer_tagline: '',
  })

  useEffect(() => {
    api.get('/vendor/settings').then((response) => setSettings((current) => ({ ...current, ...response.data })))
  }, [])

  async function save(event) {
    event.preventDefault()

    try {
      await api.put('/vendor/settings', { settings })
      toast.success('Store settings saved')
    } catch (error) {
      toast.error(error.response?.data?.message || 'Could not save settings')
    }
  }

  return (
    <div className="split">
      <form className="card form-grid" onSubmit={save}>
        <h3>Store Editor</h3>
        <label className="field"><span>Hero Headline</span><input value={settings.hero_headline} onChange={(event) => setSettings({ ...settings, hero_headline: event.target.value })} /></label>
        <label className="field"><span>Hero CTA</span><input value={settings.hero_cta} onChange={(event) => setSettings({ ...settings, hero_cta: event.target.value })} /></label>
        <label className="field"><span>Brand Color</span><input value={settings.brand_color} onChange={(event) => setSettings({ ...settings, brand_color: event.target.value })} /></label>
        <label className="field"><span>Footer Tagline</span><input value={settings.footer_tagline} onChange={(event) => setSettings({ ...settings, footer_tagline: event.target.value })} /></label>
        <button className="button">Save Settings</button>
      </form>

      <div className="card">
        <span className="eyebrow">Live Preview</span>
        <div className="hero" style={{ '--brand': settings.brand_color, background: `linear-gradient(135deg, ${settings.brand_color}, #f3b34d)` }}>
          <h2 style={{ margin: 0 }}>{settings.hero_headline || 'Your next bestselling collection starts here.'}</h2>
          <p>{settings.footer_tagline || 'A storefront you can tune live from the vendor dashboard.'}</p>
          <div className="row">
            <span className="pill">{settings.hero_cta || 'Shop now'}</span>
          </div>
        </div>
      </div>
    </div>
  )
}
