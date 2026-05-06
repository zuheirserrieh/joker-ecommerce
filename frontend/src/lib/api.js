import axios from 'axios'

export const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  const auth = localStorage.getItem('marketos-auth')

  if (auth) {
    const parsed = JSON.parse(auth)
    if (parsed?.token) {
      config.headers.Authorization = `Bearer ${parsed.token}`
    }
  }

  return config
})
