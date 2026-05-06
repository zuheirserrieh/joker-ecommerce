import { create } from 'zustand'
import { api } from '../lib/api'

const savedAuth = localStorage.getItem('marketos-auth')
const initialState = savedAuth ? JSON.parse(savedAuth) : { token: null, role: null, user: null }

export const useAuthStore = create((set) => ({
  ...initialState,
  setAuth: (payload) => {
    localStorage.setItem('marketos-auth', JSON.stringify(payload))
    set(payload)
  },
  clearAuth: () => {
    localStorage.removeItem('marketos-auth')
    set({ token: null, role: null, user: null })
  },
  loginAdmin: async (payload) => {
    const { data } = await api.post('/auth/admin/login', payload)
    localStorage.setItem('marketos-auth', JSON.stringify(data))
    set(data)
    return data
  },
  loginVendor: async (payload) => {
    const { data } = await api.post('/auth/vendor/login', payload)
    localStorage.setItem('marketos-auth', JSON.stringify(data))
    set(data)
    return data
  },
}))
