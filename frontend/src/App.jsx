import { Navigate, Route, Routes } from 'react-router-dom'
import ProtectedRoute from './components/ProtectedRoute'
import { CartProvider } from './context/CartContext'
import AdminLayout from './layouts/AdminLayout'
import StoreLayout from './layouts/StoreLayout'
import VendorLayout from './layouts/VendorLayout'
import AdminDashboardPage from './pages/admin/AdminDashboardPage'
import AdminLoginPage from './pages/admin/AdminLoginPage'
import VendorsPage from './pages/admin/VendorsPage'
import CheckoutPage from './pages/store/CheckoutPage'
import ProductDetailPage from './pages/store/ProductDetailPage'
import StoreHomePage from './pages/store/StoreHomePage'
import AiToolsPage from './pages/vendor/AiToolsPage'
import AnalyticsPage from './pages/vendor/AnalyticsPage'
import OrdersPage from './pages/vendor/OrdersPage'
import ProductsPage from './pages/vendor/ProductsPage'
import StoreEditorPage from './pages/vendor/StoreEditorPage'
import VendorDashboardPage from './pages/vendor/VendorDashboardPage'
import VendorLoginPage from './pages/vendor/VendorLoginPage'

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Navigate to="/admin/login" replace />} />
      <Route path="/admin/login" element={<AdminLoginPage />} />
      <Route path="/vendor/login" element={<VendorLoginPage />} />

      <Route
        path="/admin"
        element={
          <ProtectedRoute role="super_admin">
            <AdminLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<AdminDashboardPage />} />
        <Route path="vendors" element={<VendorsPage />} />
      </Route>

      <Route
        path="/vendor"
        element={
          <ProtectedRoute role="vendor">
            <VendorLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<VendorDashboardPage />} />
        <Route path="products" element={<ProductsPage />} />
        <Route path="orders" element={<OrdersPage />} />
        <Route path="store-editor" element={<StoreEditorPage />} />
        <Route path="ai-tools" element={<AiToolsPage />} />
        <Route path="analytics" element={<AnalyticsPage />} />
      </Route>

      <Route
        path="/:vendorSlug"
        element={
          <CartProvider>
            <StoreLayout />
          </CartProvider>
        }
      >
        <Route index element={<StoreHomePage />} />
        <Route path="products/:productId" element={<ProductDetailPage />} />
        <Route path="checkout" element={<CheckoutPage />} />
      </Route>
    </Routes>
  )
}
