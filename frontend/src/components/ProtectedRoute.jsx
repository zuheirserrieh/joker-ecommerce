import { Navigate, useLocation } from 'react-router-dom'
import { useAuthStore } from '../stores/authStore'

export default function ProtectedRoute({ children, role }) {
  const location = useLocation()
  const auth = useAuthStore()

  if (!auth.token || !auth.role) {
    return <Navigate to={role === 'super_admin' ? '/admin/login' : '/vendor/login'} replace state={{ from: location }} />
  }

  if (auth.role !== role) {
    return <Navigate to={auth.role === 'super_admin' ? '/admin' : '/vendor'} replace />
  }

  return children
}
