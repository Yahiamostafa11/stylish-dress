import { Link } from "react-router-dom";
import { useCart } from "../context/CartContext";
import { useLanguage } from "../context/LanguageContext";

export default function Cart() {
  const { t } = useLanguage();
  const { items, removeItem, updateQuantity, subtotal } = useCart();

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("cart.eyebrow")}</span>
        <h1>{t("cart.title")}</h1>
        <p className="page-sub">{t("cart.subtitle")}</p>
      </div>

      {items.length === 0 ? (
        <div className="cart-empty">
          <p className="page-sub">{t("cart.empty")}</p>
          <Link className="btn btn-rose" to="/shop">{t("cart.browseCta")}</Link>
        </div>
      ) : (
        <div className="order-card">
          {items.map((item) => (
            <div className="cart-item" key={item.cartItemId}>
              <div className="item-thumb">
                {item.image && <img src={item.image} alt={item.name} style={{ width: "100%", height: "100%", objectFit: "cover" }} />}
              </div>
              <div className="item-info cart-item-info">
                <h4>{item.name}</h4>
                {item.measurements && (
                  <p className="cart-item-meta">
                    {Object.entries(item.measurements).map(([label, value]) => `${label}: ${value}`).join(" · ")}
                  </p>
                )}
                {item.price != null && (
                  <strong className="cart-item-price">{item.price} {t("amnahi.currency")}</strong>
                )}
              </div>
              <div className="cart-qty">
                <button type="button" onClick={() => updateQuantity(item.cartItemId, item.quantity - 1)} aria-label="-">−</button>
                <span>{item.quantity}</span>
                <button type="button" onClick={() => updateQuantity(item.cartItemId, item.quantity + 1)} aria-label="+">+</button>
              </div>
              <button type="button" className="cart-remove" onClick={() => removeItem(item.cartItemId)}>
                {t("cart.remove")}
              </button>
            </div>
          ))}

          <div className="cart-summary">
            <span>{t("cart.subtotal")}</span>
            <strong>{subtotal.toFixed(0)} {t("amnahi.currency")}</strong>
          </div>

          <Link className="btn btn-rose" style={{ width: "100%" }} to="/checkout">
            {t("cart.checkoutCta")}
          </Link>
        </div>
      )}
    </div>
  );
}
