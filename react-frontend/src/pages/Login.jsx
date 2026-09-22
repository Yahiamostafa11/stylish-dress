import { useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { loginAccount } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import PasswordField from "../components/PasswordField";

export default function Login() {
  const { login } = useAuth();
  const { t } = useLanguage();
  const navigate = useNavigate();
  const location = useLocation();
  const redirectTo = location.state?.from ?? "/sell";

  const [form, setForm] = useState({ email: "", password: "" });
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const onSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setBusy(true);
    try {
      const res = await loginAccount(form);
      login(res.token, res.user);
      navigate(redirectTo, { replace: true });
    } catch (err) {
      setError(err.message || t("login.error"));
    } finally {
      setBusy(false);
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("login.eyebrow")}</span>
        <h1>{t("login.title")}</h1>
      </div>

      <form className="order-card" onSubmit={onSubmit} style={{ maxWidth: 420 }}>
        <div className="field" style={{ marginBottom: 16 }}>
          <label><i>*</i>{t("login.email")}</label>
          <input required type="email" value={form.email} onChange={(e) => setField("email", e.target.value)} />
        </div>
        <div className="field" style={{ marginBottom: 8 }}>
          <label><i>*</i>{t("login.password")}</label>
          <PasswordField required value={form.password} onChange={(e) => setField("password", e.target.value)} />
        </div>

        {error && <p style={{ color: "var(--rose-600)", fontSize: 12.5, margin: "8px 0" }}>{error}</p>}

        <button className="btn btn-rose" style={{ width: "100%", marginTop: 12 }} type="submit" disabled={busy}>
          {busy ? t("login.submitting") : t("login.submit")}
        </button>

        <p style={{ textAlign: "center", fontSize: 12.5, color: "var(--muted-600)", marginTop: 16 }}>
          {t("login.noAccount")} <Link to="/register" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("login.registerLink")}</Link>
        </p>
      </form>
    </div>
  );
}
