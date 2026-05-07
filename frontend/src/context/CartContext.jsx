import { useEffect, useState } from 'react'
import { CartContext } from './CartContextValue'

export function CartProvider({ children }) {
  const [items, setItems] = useState(() => {
    const saved = localStorage.getItem('marketos-cart')
    return saved ? JSON.parse(saved) : []
  })

  useEffect(() => {
    localStorage.setItem('marketos-cart', JSON.stringify(items))
  }, [items])

  const addItem = (product) => {
    setItems((current) => {
      const exists = current.find((item) => item.id === product.id)

      if (exists) {
        return current.map((item) => item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item)
      }

      return [...current, { ...product, quantity: 1 }]
    })
  }

  const updateQuantity = (id, quantity) => {
    setItems((current) => current.map((item) => item.id === id ? { ...item, quantity } : item).filter((item) => item.quantity > 0))
  }

  const clear = () => setItems([])

  return (
    <CartContext.Provider value={{ items, addItem, updateQuantity, clear }}>
      {children}
    </CartContext.Provider>
  )
}
