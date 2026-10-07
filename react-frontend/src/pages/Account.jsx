import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { changePassword, requestEmailChange } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import PasswordField from "../components/PasswordField";

const msgStyle = (ok) => ({ color: ok ? "var(--mint-700, #3d6b5c)" : "var(--rose-600)", fontSize: 12.5, margin: "8px 0" });

function PasswordCard() {
  const { token, login, user } = useAuth();
  const { t } = useLanguage();
  const [form, setForm] = useState({ current: "", next: "", confirm: "" });
  const [busy, setBusy] = useState(false);
  const [msg, setMsg] = useState(null);

  const setField = (k, v) => setForm((f) => ({ ...f, [k]: v }));

  const onSubmit = async (e) => {
    e.preventDefault();
    setMsg(null);
    if (form.next !== form.confirm) {
      setMsg({ ok: false, text: t("reset.mismatch") });
      return;
    }
    setBusy(true);
    try {
      const res = await changePassword({ currentPassword: form.current, newPassword: form.next });
      login(res.token ?? token, res.user ?? user);
      setForm({ current: "", next: "", confirm: "" });
      setMsg({ ok: true, text: t("account.passwordDone") });
    } catch (err) {
      setMsg({ ok: false, text: err.message || t("account.error") });
    } finally {
      setBusy(false);
    }
  };

  return (
    <form className="order-card" onSubmit={onSubmit}>
      <h2 style={{ fontSize: 16, margin: "0 0 16px" }}>{t("account.passwordTitle")}</h2>
      <div className="field" style={{ marginBottom: 14 }}>
        <label><i>*</i>{t("account.currentPassword")}</label>
        <PasswordField required value={form.current} onChange={(e) => setField("current", e.target.value)} />
      </div>
      <div className="field" style={{ marginBottom: 14 }}>
        <label><i>*</i>{t("reset.newPassword")}</label>
        <PasswordField required minLength={6} value={form.next} onChange={(e) => setField("next", e.target.value)} />
      </div>
      <div className="field" style={{ marginBottom: 6 }}>
        <label><i>*</i>{t("reset.confirm")}</label>
        <PasswordField required minLength={6} value={form.confirm} onChange={(e) => setField("confirm", e.target.value)} />
      </div>
      {msg && <p style={msgStyle(msg.ok)}>{msg.text}</p>}
      <button className="btn btn-rose" style={{ width: "100%", marginTop: 8 }} type="submit" disabled={busy}>
        {busy ? t("reset.saving") : t("account.passwordSubmit")}
      </button>
    </form>
  );
}

function EmailCard() {
  const { user } = useAuth();
  const { t } = useLanguage();
  const [form, setForm] = useState({ email: "", password: "" });
  const [busy, setBusy] = useState(false);
  const [msg, setMsg] = useState(null);

  const setField = (k, v) => setForm((f) => ({ ...f, [k]: v }));

  const onSubmit = async (e) => {
    e.preventDefault();
    setMsg(null);
    setBusy(true);
    try {
      await requestEmailChange({ newEmail: form.email, currentPassword: form.password });
      setMsg({ ok: true, text: t("account.emailSent") });
      setForm({ email: "", password: "" });
    } catch (err) {
      setMsg({ ok: false, text: err.message || t("account.error") });
    } finally {
      setBusy(false);
    }
  };

  return (
    <form className="order-card" onSubmit={onSubmit}>
      <h2 style={{ fontSize: 16, margin: "0 0 6px" }}>{t("account.emailTitle")}</h2>
      <p style={{ fontSize: 12.5, color: "var(--muted-500)", margin: "0 0 16px" }}>
        {t("account.emailCurrent")} <strong dir="ltr">{user?.email}</strong>
      </p>
      <div className="field" style={{ marginBottom: 14 }}>
        <label><i>*</i>{t("account.newEmail")}</label>
        <input required type="email" value={form.email} onChange={(e) => setField("email", e.target.value)} />
      </div>
      <div className="field" style={{ marginBottom: 6 }}>
        <label><i>*</i>{t("account.currentPassword")}</label>
        <PasswordField required value={form.password} onChange={(e) => setField("password", e.target.value)} />
      </div>
      {msg && <p style={msgStyle(msg.ok)}>{msg.text}</p>}
      <button className="btn btn-rose" style={{ width: "100%", marginTop: 8 }} type="submit" disabled={busy}>
        {busy ? t("forgot.sending") : t("account.emailSubmit")}
      </button>
    </form>
  );
}

export default function Account() {
  const { user, logout } = useAuth();
  const { t } = useLanguage();
  const navigate = useNavigate();

  const onLogout = () => {
    logout();
    navigate("/", { replace: true });
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("account.eyebrow")}</span>
        <h1>{user?.name || t("account.title")}</h1>
        <p className="page-sub" dir="ltr">{user?.email}</p>
      </div>

      <div style={{ display: "grid", gap: 20, maxWidth: 440, margin: "0 auto 28px" }}>
        <PasswordCard />
        <EmailCard />
        <div style={{ display: "flex", gap: 10 }}>
          <Link to="/messages" className="btn btn-outline" style={{ flex: 1, textAlign: "center" }}>{t("header.messages")}</Link>
          <button type="button" className="btn btn-outline" style={{ flex: 1 }} onClick={onLogout}>{t("header.logout")}</button>
        </div>
      </div>
    </div>
  );
}
