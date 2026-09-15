import { useState } from "react";
import { Link, Navigate } from "react-router-dom";
import { submitCheckout } from "../api/client";
import { useCart } from "../context/CartContext";
import { useLanguage } from "../context/LanguageContext";
import { useAuth } from "../context/AuthContext";

export default function Checkout() {
  const { t } = useLanguage();
  const { items, subtotal, clearCart } = useCart();
  const { user } = useAuth();

  const [form, setForm] = useState({
    name: user?.name ?? "",
    email: user?.email ?? "",
    phone: "",
    address_1: "",
    city: "",
    country: "EG",
  });
  const [status, setStatus] = useState("idle"); // idle | placing | redirecting | error
  const [error, setError] = useState("");

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  if (items.length === 0 && status === "idle") {
    return (
      <div className="wrap page">
        <div className="page-head">
          <h1>{t("checkout.title")}</h1>
          <p className="page-sub">{t("checkout.emptyCart")}</p>
        </div>
        <div style={{ textAlign: "center" }}>
          <Link className="btn btn-rose" to="/shop">{t("cart.browseCta")}</Link>
        </div>
      </div>
    );
  }

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("placing");
    setError("");
    try {
      const result = await submitCheckout({
        customer: form,
        items: items.map((item) => ({
          product_id: item.productId,
          variation_id: item.variationId ?? undefined,
          quantity: item.quantity,
          measurements: item.measurements ?? undefined,
          notes: item.notes ?? undefined,
        })),
      });
      if (!result.pay_url) {
        throw new Error("no pay_url");
      }
      setStatus("redirecting");
      clearCart();
      window.location.href = result.pay_url;
    } catch (err) {
      setStatus("error");
      setError(err.message || t("checkout.error"));
    }
  };

  if (status === "redirecting") {
    return (
      <div className="wrap page">
        <p style={{ textAlign: "center", color: "var(--muted-500)" }}>{t("checkout.redirecting")}</p>
      </div>
    );
  }

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("checkout.eyebrow")}</span>
        <h1>{t("checkout.title")}</h1>
        <p className="page-sub">{t("checkout.subtitle")}</p>
      </div>

      <div className="order-card checkout-layout">
        <form className="checkout-form" onSubmit={handleSubmit}>
          <div className="field-grid">
            <div className="field">
              <label><i>*</i>{t("checkout.nameLabel")}</label>
              <div className="input-wrap">
                <input required value={form.name} onChange={(e) => setField("name", e.target.value)} />
              </div>
            </div>
            <div className="field">
              <label><i>*</i>{t("checkout.emailLabel")}</label>
              <div className="input-wrap">
                <input required type="email" value={form.email} onChange={(e) => setField("email", e.target.value)} />
              </div>
            </div>
            <div className="field">
              <label><i>*</i>{t("checkout.phoneLabel")}</label>
              <div className="input-wrap">
                <input required value={form.phone} onChange={(e) => setField("phone", e.target.value)} />
              </div>
            </div>
            <div className="field">
              <label><i>*</i>{t("checkout.cityLabel")}</label>
              <div className="input-wrap">
                <input required value={form.city} onChange={(e) => setField("city", e.target.value)} />
              </div>
            </div>
            <div className="field" style={{ gridColumn: "1 / -1" }}>
              <label><i>*</i>{t("checkout.addressLabel")}</label>
              <div className="input-wrap">
                <input required value={form.address_1} onChange={(e) => setField("address_1", e.target.value)} />
              </div>
            </div>
          </div>

          {status === "error" && (
            <p style={{ color: "var(--rose-600)", fontSize: 13, marginBottom: 14 }}>{error}</p>
          )}

          <button className="btn btn-rose" style={{ width: "100%" }} disabled={status === "placing"}>
            {status === "placing" ? t("checkout.placing") : t("checkout.placeOrder")}
          </button>
        </form>

        <aside className="checkout-summary">
          <h3>{t("checkout.orderSummary")}</h3>
          {items.map((item) => (
            <div className="checkout-summary-row" key={item.cartItemId}>
              <span>{item.name} × {item.quantity}</span>
              <strong>{((item.price || 0) * item.quantity).toFixed(0)} {t("amnahi.currency")}</strong>
            </div>
          ))}
          <div className="checkout-summary-row checkout-summary-total">
            <span>{t("cart.subtotal")}</span>
            <strong>{subtotal.toFixed(0)} {t("amnahi.currency")}</strong>
          </div>
        </aside>
      </div>
    </div>
  );
}
