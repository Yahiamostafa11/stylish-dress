import { useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { registerAccount } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import PasswordField from "../components/PasswordField";

export default function Register() {
  const { login } = useAuth();
  const { t } = useLanguage();
  const navigate = useNavigate();
  const location = useLocation();
  const redirectTo = location.state?.from ?? "/sell";

  const [form, setForm] = useState({ name: "", email: "", phone: "", password: "" });
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const onSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setBusy(true);
    try {
      const res = await registerAccount(form);
      login(res.token, res.user);
      navigate(redirectTo, { replace: true });
    } catch (err) {
      setError(err.message || t("register.error"));
    } finally {
      setBusy(false);
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("register.eyebrow")}</span>
        <h1>{t("register.title")}</h1>
      </div>

      <form className="order-card" onSubmit={onSubmit} style={{ maxWidth: 420 }}>
        <div className="field" style={{ marginBottom: 16 }}>
          <label><i>*</i>{t("register.name")}</label>
          <input required value={form.name} onChange={(e) => setField("name", e.target.value)} />
        </div>
        <div className="field" style={{ marginBottom: 16 }}>
          <label><i>*</i>{t("register.email")}</label>
          <input required type="email" value={form.email} onChange={(e) => setField("email", e.target.value)} />
        </div>
        <div className="field" style={{ marginBottom: 16 }}>
          <label>{t("register.phone")}</label>
          <input type="tel" value={form.phone} onChange={(e) => setField("phone", e.target.value)} />
        </div>
        <div className="field" style={{ marginBottom: 8 }}>
          <label><i>*</i>{t("register.password")}</label>
          <PasswordField required minLength={6} value={form.password} onChange={(e) => setField("password", e.target.value)} />
        </div>

        {error && <p style={{ color: "var(--rose-600)", fontSize: 12.5, margin: "8px 0" }}>{error}</p>}

        <button className="btn btn-rose" style={{ width: "100%", marginTop: 12 }} type="submit" disabled={busy}>
          {busy ? t("register.submitting") : t("register.submit")}
        </button>

        <p style={{ textAlign: "center", fontSize: 12.5, color: "var(--muted-600)", marginTop: 16 }}>
          {t("register.hasAccount")} <Link to="/login" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("register.loginLink")}</Link>
        </p>
      </form>
    </div>
  );
}
