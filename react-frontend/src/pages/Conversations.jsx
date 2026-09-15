import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { fetchConversations } from "../api/client";
import RequireAuth from "../components/RequireAuth";
import { useLanguage } from "../context/LanguageContext";

function ConversationsList() {
  const { t } = useLanguage();
  const [conversations, setConversations] = useState([]);
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    let cancelled = false;
    fetchConversations()
      .then((data) => {
        if (!cancelled) {
          setConversations(data);
          setStatus("ready");
        }
      })
      .catch(() => {
        if (!cancelled) setStatus("error");
      });
    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("messages.eyebrow")}</span>
        <h1>{t("messages.title")}</h1>
      </div>

      {status === "loading" && <p className="state-msg">{t("messages.loading")}</p>}
      {status === "error" && <p className="state-msg error">{t("messages.error")}</p>}
      {status === "ready" && conversations.length === 0 && (
        <p className="state-msg">{t("messages.empty")}</p>
      )}

      {status === "ready" && conversations.length > 0 && (
        <div style={{ maxWidth: 640, margin: "0 auto", display: "flex", flexDirection: "column", gap: 10 }}>
          {conversations.map((c) => (
            <Link key={c.id} to={`/messages/${c.id}`} className="contact-item" style={{ cursor: "pointer" }}>
              <span className="ci-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" /></svg>
              </span>
              <div>
                <strong>{c.other_user_name} — {c.listing_title}</strong>
                <span>{c.last_message || t("messages.startChat")}</span>
              </div>
            </Link>
          ))}
        </div>
      )}
    </div>
  );
}

export default function Conversations() {
  return (
    <RequireAuth>
      <ConversationsList />
    </RequireAuth>
  );
}
