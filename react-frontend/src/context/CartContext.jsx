import { createContext, useContext, useEffect, useState } from "react";

const CartContext = createContext(null);
const STORAGE_KEY = "styliiiish_cart";

function loadCart() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    const parsed = raw ? JSON.parse(raw) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

export function CartProvider({ children }) {
  const [items, setItems] = useState(loadCart);

  useEffect(() => {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    } catch {
      // ignore write failures (private mode / storage disabled)
    }
  }, [items]);

  const addItem = ({ productId, variationId = null, name, image, price, quantity = 1, measurements = null, sizeType = null, unit = null, notes = "" }) => {
    const cartItemId = `${productId}-${Date.now()}`;
    setItems((prev) => [
      ...prev,
      { cartItemId, productId, variationId, name, image, price, quantity, measurements, sizeType, unit, notes },
    ]);
    return cartItemId;
  };

  const removeItem = (cartItemId) => {
    setItems((prev) => prev.filter((item) => item.cartItemId !== cartItemId));
  };

  const updateQuantity = (cartItemId, quantity) => {
    const qty = Math.max(1, Number(quantity) || 1);
    setItems((prev) => prev.map((item) => (item.cartItemId === cartItemId ? { ...item, quantity: qty } : item)));
  };

  const clearCart = () => setItems([]);

  const count = items.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = items.reduce((sum, item) => sum + (Number(item.price) || 0) * item.quantity, 0);

  return (
    <CartContext.Provider value={{ items, addItem, removeItem, updateQuantity, clearCart, count, subtotal }}>
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const ctx = useContext(CartContext);
  if (!ctx) throw new Error("useCart must be used within CartProvider");
  return ctx;
}
