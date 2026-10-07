import { useEffect, useRef, useState } from "react";
import { Link, useSearchParams } from "react-router-dom";
import { confirmEmailChange } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";

export default function ConfirmEmail() {
  const { t } = useLanguage();
  const { token: sessionToken, user, login } = useAuth();
  const [params] = useSearchParams();
  const token = params.get("token") ?? "";
  const [state, setState] = useState(token ? "working" : "error");
  const [message, setMessage] = useState("");
  const started = useRef(false);

  useEffect(() => {
    // Single-use token: guard against React StrictMode firing the effect twice.
    if (!token || started.current) return;
    started.current = true;
    confirmEmailChange(token)
      .then((res) => {
        // If she's logged in as this same account, keep the stored profile in sync.
        if (sessionToken && user && Number(user.id) === Number(res.user.id)) {
          login(sessionToken, res.user);
        }
        setMessage(res.user.email);
        setState("done");
      })
      .catch((err) => {
        setMessage(err.message || "");
        setState("error");
      });
  }, [token, sessionToken, user, login]);

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("account.eyebrow")}</span>
        <h1>{t("confirm.title")}</h1>
      </div>

      <div className="order-card" style={{ maxWidth: 420, textAlign: "center" }}>
        {state === "working" && <p className="state-msg" style={{ padding: 0 }}>{t("confirm.working")}</p>}
        {state === "done" && (
          <>
            <p className="state-msg" style={{ padding: 0 }}>{t("confirm.done")}</p>
            <p dir="ltr" style={{ fontWeight: 600, margin: "8px 0 16px" }}>{message}</p>
            <Link to="/login" className="btn btn-rose">{t("header.login")}</Link>
          </>
        )}
        {state === "error" && (
          <>
            <p className="state-msg error" style={{ padding: 0 }}>{message || t("confirm.invalid")}</p>
            <p style={{ marginTop: 16, fontSize: 12.5 }}>
              <Link to="/account" style={{ color: "var(--rose-600)", fontWeight: 600 }}>{t("confirm.back")}</Link>
            </p>
          </>
        )}
      </div>
    </div>
  );
}
