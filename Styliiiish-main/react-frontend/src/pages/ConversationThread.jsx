import { useEffect, useRef, useState } from "react";
import { useParams } from "react-router-dom";
import { fetchMessages, sendMessage } from "../api/client";
import RequireAuth from "../components/RequireAuth";
import { useLanguage } from "../context/LanguageContext";

const POLL_MS = 4000;

function Thread() {
  const { t } = useLanguage();
  const { id } = useParams();
  const [messages, setMessages] = useState([]);
  const [myId, setMyId] = useState(null);
  const [status, setStatus] = useState("loading");
  const [draft, setDraft] = useState("");
  const [sending, setSending] = useState(false);
  const bottomRef = useRef(null);

  useEffect(() => {
    let cancelled = false;

    const load = () => {
      fetchMessages(id)
        .then((res) => {
          if (!cancelled) {
            setMessages(res.data);
            setMyId(res.my_id);
            setStatus("ready");
          }
        })
        .catch(() => {
          if (!cancelled) setStatus("error");
        });
    };

    load();
    const interval = setInterval(load, POLL_MS);
    return () => {
      cancelled = true;
      clearInterval(interval);
    };
  }, [id]);

  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages.length]);

  const onSend = async (e) => {
    e.preventDefault();
    const body = draft.trim();
    if (!body) return;
    setSending(true);
    try {
      await sendMessage(id, body);
      setDraft("");
      const res = await fetchMessages(id);
      setMessages(res.data);
    } catch {
      // silently ignore — next poll will retry the fetch
    } finally {
      setSending(false);
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head" style={{ marginBottom: 16 }}>
        <span className="story-eyebrow">{t("messages.eyebrow")}</span>
        <h1>{t("thread.title")}</h1>
      </div>

      <div className="chat-thread">
        {status === "loading" && <p className="state-msg">{t("thread.loading")}</p>}
        {status === "error" && <p className="state-msg error">{t("thread.error")}</p>}
        {status === "ready" && messages.length === 0 && (
          <p className="state-msg">{t("thread.empty")}</p>
        )}
        {status === "ready" &&
          messages.map((m) => (
            <div key={m.id} className={`chat-bubble${Number(m.sender_user_id) === Number(myId) ? " mine" : ""}`}>
              {m.body}
            </div>
          ))}
        <div ref={bottomRef} />
      </div>

      <form className="chat-input-row" onSubmit={onSend}>
        <input
          type="text"
          placeholder={t("thread.placeholder")}
          value={draft}
          onChange={(e) => setDraft(e.target.value)}
        />
        <button className="btn btn-rose" type="submit" disabled={sending || !draft.trim()}>{t("thread.send")}</button>
      </form>
    </div>
  );
}

export default function ConversationThread() {
  return (
    <RequireAuth>
      <Thread />
    </RequireAuth>
  );
}
