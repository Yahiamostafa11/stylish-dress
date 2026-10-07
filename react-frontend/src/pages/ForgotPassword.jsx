import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { forgotPassword, resetPassword } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import PasswordField from "../components/PasswordField";

const errStyle = { color: "var(--rose-600)", fontSize: 12.5, margin: "8px 0" };

export default function ForgotPassword() {
  const { t } = useLanguage();
  const { login } = useAuth();
  const navigate = useNavigate();

  const [step, setStep] = useState("email"); // "email" -> "code"
  const [email, setEmail] = useState("");
  const [code, setCode] = useState("");
  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState("");
  const [resent, setResent] = useState(false);

  const sendCode = async (e) => {
    e?.preventDefault();
    setError("");
    setBusy(true);
    try {
      await forgotPassword(email.trim());
      setStep("code");
    } catch (err) {
      setError(err.message || t("forgot.error"));
    } finally {
      setBusy(false);
    }
  };

  const resend = async () => {
    setError("");
    setResent(false);
    setBusy(true);
    try {
      await forgotPassword(email.trim());
      setResent(true);
    } catch (err) {
      setError(err.message || t("forgot.error"));
    } finally {
      setBusy(false);
    }
  };

  const submitReset = async (e) => {
    e.preventDefault();
    setError("");
    if (password !== confirm) {
      setError(t("reset.mismatch"));
      return;
    }
    setBusy(true);
    try {
      const res = await resetPassword({ email: email.trim(), code: code.trim(), password });
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
        <span className="story-eyebrow">{t("forgot.eyebrow")}</span>
        <h1>{t("forgot.title")}</h1>
        <p className="page-sub">{step === "email" ? t("forgot.subtitle") : t("forgot.codeSub")}</p>
      </div>

      <div className="order-card" style={{ maxWidth: 420 }}>
        {step === "email" ? (
          <form onSubmit={sendCode}>
            <div className="field" style={{ marginBottom: 16 }}>
              <label><i>*</i>{t("login.email")}</label>
              <input required type="email" autoComplete="email" value={email} onChange={(e) => setEmail(e.target.value)} />
            </div>
            {error && <p style={errStyle}>{error}</p>}
            <button className="btn btn-rose" style={{ width: "100%" }} type="submit" disabled={busy}>
              {busy ? t("forgot.sending") : t("forgot.submit")}
            </button>
          </form>
        ) : (
          <form onSubmit={submitReset}>
            <p style={{ fontSize: 12.5, color: "var(--muted-500)", margin: "0 0 14px" }}>
              {t("forgot.sentTo")} <strong dir="ltr">{email}</strong>
            </p>
            <div className="field" style={{ marginBottom: 14 }}>
              <label><i>*</i>{t("forgot.code")}</label>
              <input
                required
                inputMode="numeric"
                autoComplete="one-time-code"
                pattern="[0-9]{6}"
                maxLength={6}
                dir="ltr"
                placeholder="• • • • • •"
                style={{ textAlign: "center", letterSpacing: 8, fontSize: 20, fontWeight: 600 }}
                value={code}
                onChange={(e) => setCode(e.target.value.replace(/\D/g, ""))}
              />
            </div>
            <div className="field" style={{ marginBottom: 14 }}>
              <label><i>*</i>{t("reset.newPassword")}</label>
              <PasswordField required minLength={6} value={password} onChange={(e) => setPassword(e.target.value)} />
            </div>
            <div className="field" style={{ marginBottom: 6 }}>
              <label><i>*</i>{t("reset.confirm")}</label>
              <PasswordField required minLength={6} value={confirm} onChange={(e) => setConfirm(e.target.value)} />
            </div>
            {error && <p style={errStyle}>{error}</p>}
            {resent && <p style={{ ...errStyle, color: "var(--mint-700, #3d6b5c)" }}>{t("forgot.resent")}</p>}
            <button className="btn btn-rose" style={{ width: "100%", marginTop: 8 }} type="submit" disabled={busy || code.length !== 6}>
              {busy ? t("reset.saving") : t("reset.submit")}
            </button>
            <p style={{ textAlign: "center", fontSize: 12.5, marginTop: 14 }}>
              {t("forgot.noCode")}{" "}
              <button type="button" onClick={resend} disabled={busy} style={{ background: "none", border: 0, padding: 0, cursor: "pointer", color: "var(--rose-600)", fontWeight: 600, font: "inherit" }}>
                {t("forgot.resend")}
              </button>
            </p>
          </form>
        )}
        <p style={{ textAlign: "center", fontSize: 12.5, marginTop: 16 }}>
          <Link to="/login" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("forgot.back")}</Link>
        </p>
      </div>
    </div>
  );
}
