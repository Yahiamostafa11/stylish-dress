import { useState } from "react";
import { Link, useNavigate, useSearchParams } from "react-router-dom";
import { resetPassword } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import PasswordField from "../components/PasswordField";

export default function ResetPassword() {
  const { login } = useAuth();
  const { t } = useLanguage();
  const navigate = useNavigate();
  const [params] = useSearchParams();
  const token = params.get("token") ?? "";

  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);

  const onSubmit = async (e) => {
    e.preventDefault();
    setError("");
    if (password !== confirm) {
      setError(t("reset.mismatch"));
      return;
    }
    setBusy(true);
    try {
      const res = await resetPassword({ token, password });
      login(res.token, res.user);
      navigate("/sell", { replace: true });
    } catch (err) {
      setError(err.message || t("reset.error"));
    } finally {
      setBusy(false);
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("reset.eyebrow")}</span>
        <h1>{t("reset.title")}</h1>
      </div>

      <div className="order-card" style={{ maxWidth: 420 }}>
        {!token ? (
          <>
            <p className="state-msg error" style={{ padding: 0, textAlign: "start" }}>{t("reset.noToken")}</p>
            <p style={{ textAlign: "center", fontSize: 12.5, marginTop: 16 }}>
              <Link to="/forgot-password" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("reset.requestNew")}</Link>
            </p>
          </>
        ) : (
          <form onSubmit={onSubmit}>
            <div className="field" style={{ marginBottom: 16 }}>
              <label><i>*</i>{t("reset.newPassword")}</label>
              <PasswordField required minLength={6} value={password} onChange={(e) => setPassword(e.target.value)} />
            </div>
            <div className="field" style={{ marginBottom: 8 }}>
              <label><i>*</i>{t("reset.confirm")}</label>
              <PasswordField required minLength={6} value={confirm} onChange={(e) => setConfirm(e.target.value)} />
            </div>
            {error && <p style={{ color: "var(--rose-600)", fontSize: 12.5, margin: "8px 0" }}>{error}</p>}
            <button className="btn btn-rose" style={{ width: "100%", marginTop: 12 }} type="submit" disabled={busy}>
              {busy ? t("reset.saving") : t("reset.submit")}
            </button>
            <p style={{ textAlign: "center", fontSize: 12.5, marginTop: 16 }}>
              <Link to="/forgot-password" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("reset.requestNew")}</Link>
            </p>
          </form>
        )}
      </div>
    </div>
  );
}
