import { useState } from "react";
import { Link } from "react-router-dom";
import { forgotPassword } from "../api/client";
import { useLanguage } from "../context/LanguageContext";

export default function ForgotPassword() {
  const { t } = useLanguage();
  const [email, setEmail] = useState("");
  const [status, setStatus] = useState("idle");
  const [error, setError] = useState("");

  const onSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setStatus("sending");
    try {
      await forgotPassword(email);
      setStatus("sent");
    } catch (err) {
      setError(err.message || t("forgot.error"));
      setStatus("idle");
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("forgot.eyebrow")}</span>
        <h1>{t("forgot.title")}</h1>
        <p className="page-sub">{t("forgot.subtitle")}</p>
      </div>

      <div className="order-card" style={{ maxWidth: 420 }}>
        {status === "sent" ? (
          <p className="state-msg" style={{ padding: 0, textAlign: "start" }}>{t("forgot.sent")}</p>
        ) : (
          <form onSubmit={onSubmit}>
            <div className="field" style={{ marginBottom: 16 }}>
              <label><i>*</i>{t("login.email")}</label>
              <input required type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
            </div>
            {error && <p style={{ color: "var(--rose-600)", fontSize: 12.5, margin: "8px 0" }}>{error}</p>}
            <button className="btn btn-rose" style={{ width: "100%" }} type="submit" disabled={status === "sending"}>
              {status === "sending" ? t("forgot.sending") : t("forgot.submit")}
            </button>
          </form>
        )}
        <p style={{ textAlign: "center", fontSize: 12.5, marginTop: 16 }}>
          <Link to="/login" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("forgot.back")}</Link>
        </p>
      </div>
    </div>
  );
}
