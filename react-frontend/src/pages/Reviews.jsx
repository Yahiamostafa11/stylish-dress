import { useEffect, useState } from "react";
import { useLanguage } from "../context/LanguageContext";
import { submitReview } from "../api/client";

const SOURCES = ["google", "facebook", "instagram", "messenger", "whatsapp"];

function SourceIcon({ source }) {
  const common = { width: 16, height: 16, viewBox: "0 0 24 24", "aria-hidden": true };
  switch (source) {
    case "google":
      return (
        <svg {...common}>
          <path fill="#4285F4" d="M21.6 12.2c0-.7-.1-1.3-.2-1.9H12v3.7h5.4a4.6 4.6 0 0 1-2 3v2.5h3.2c1.9-1.7 3-4.3 3-7.3z" />
          <path fill="#34A853" d="M12 22c2.7 0 5-.9 6.6-2.4l-3.2-2.5c-.9.6-2 1-3.4 1-2.6 0-4.8-1.8-5.6-4.1H3.1v2.6A10 10 0 0 0 12 22z" />
          <path fill="#FBBC05" d="M6.4 14c-.2-.6-.3-1.3-.3-2s.1-1.4.3-2V7.4H3.1a10 10 0 0 0 0 9.2z" />
          <path fill="#EA4335" d="M12 5.9c1.5 0 2.8.5 3.8 1.5l2.9-2.9A10 10 0 0 0 3.1 7.4L6.4 10c.8-2.3 3-4.1 5.6-4.1z" />
        </svg>
      );
    case "facebook":
      return (
        <svg {...common}>
          <circle cx="12" cy="12" r="11" fill="#1877F2" />
          <path fill="#fff" d="M13.3 20v-7h2.3l.4-2.8h-2.7V8.5c0-.8.2-1.3 1.4-1.3H16V4.7c-.3 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.6v2H8v2.8h2.4v7z" />
        </svg>
      );
    case "instagram":
      return (
        <svg {...common}>
          <defs>
            <linearGradient id="igg" x1="0" y1="1" x2="1" y2="0">
              <stop offset="0" stopColor="#feda75" />
              <stop offset=".35" stopColor="#fa7e1e" />
              <stop offset=".65" stopColor="#d62976" />
              <stop offset="1" stopColor="#4f5bd5" />
            </linearGradient>
          </defs>
          <rect x="2" y="2" width="20" height="20" rx="6" fill="url(#igg)" />
          <circle cx="12" cy="12" r="4.2" fill="none" stroke="#fff" strokeWidth="1.8" />
          <circle cx="17.2" cy="6.8" r="1.2" fill="#fff" />
        </svg>
      );
    case "messenger":
      return (
        <svg {...common}>
          <defs>
            <linearGradient id="msg" x1="0" y1="1" x2="1" y2="0">
              <stop offset="0" stopColor="#0099ff" />
              <stop offset="1" stopColor="#a033ff" />
            </linearGradient>
          </defs>
          <circle cx="12" cy="12" r="11" fill="url(#msg)" />
          <path fill="#fff" d="M5.8 14.6 9.4 9l2.7 2.7L15 9l3.200 5.600-3-1.900-2.900 2.700-2.700-2.700z" />
        </svg>
      );
    default:
      return (
        <svg {...common}>
          <circle cx="12" cy="12" r="11" fill="#25D366" />
          <path fill="#fff" d="M12 5.5a6.500 6.500 0 0 0-5.600 9.800l-.9 3.200 3.300-.9A6.500 6.500 0 1 0 12 5.500zm3.300 8.800c-.2.500-1 .9-1.400.9-.4.100-.8.100-2.600-.6-2.200-.9-3.600-3.100-3.700-3.300-.1-.1-.9-1.200-.9-2.300s.6-1.600.8-1.900c.2-.2.400-.3.600-.3h.4c.1 0 .3 0 .4.300l.6 1.500c.1.100.1.300 0 .4l-.3.400-.3.300c-.1.100-.2.300-.1.400.1.200.6 1 1.300 1.600.9.800 1.700 1 1.900 1.100.2.100.3.100.4-.1l.6-.7c.1-.2.300-.2.500-.1l1.500.7c.2.100.3.200.4.300.1.200.1.600-.1 1z" />
        </svg>
      );
  }
}

export default function Reviews() {
  const { t } = useLanguage();
  const [images, setImages] = useState([]);
  const [cards, setCards] = useState([]);
  const [filter, setFilter] = useState("all");
  const [open, setOpen] = useState(null);
  const [form, setForm] = useState({ name: "", message: "" });
  const [status, setStatus] = useState("idle");

  useEffect(() => {
    fetch("/reviews/manifest.json")
      .then((res) => (res.ok ? res.json() : []))
      .then((list) => setImages(Array.isArray(list) ? list : []))
      .catch(() => setImages([]));
    fetch("/reviews/cards/manifest.json")
      .then((res) => (res.ok ? res.json() : []))
      .then((list) => setCards(Array.isArray(list) ? list : []))
      .catch(() => setCards([]));
  }, []);

  useEffect(() => {
    if (!open) return undefined;
    const onKey = (e) => e.key === "Escape" && setOpen(null);
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open]);

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const onSubmit = async (e) => {
    e.preventDefault();
    setStatus("sending");
    try {
      await submitReview(form);
      setStatus("sent");
      setForm({ name: "", message: "" });
    } catch {
      setStatus("error");
    }
  };

  const visible = filter === "all" ? cards : cards.filter((c) => c.source === filter);
  const present = SOURCES.filter((s) => cards.some((c) => c.source === s));

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("reviews.eyebrow")}</span>
        <h1>{t("reviews.title")}</h1>
        <p className="page-sub">{t("reviews.subtitle")}</p>
      </div>

      {cards.length > 0 && (
        <>
          <div className="rv-filters">
            <button type="button" className={`rv-chip${filter === "all" ? " active" : ""}`} onClick={() => setFilter("all")}>
              {t("reviews.all")} <b>{cards.length}</b>
            </button>
            {present.map((s) => (
              <button key={s} type="button" className={`rv-chip${filter === s ? " active" : ""}`} onClick={() => setFilter(s)}>
                <SourceIcon source={s} /> {t(`reviews.src.${s}`)} <b>{cards.filter((c) => c.source === s).length}</b>
              </button>
            ))}
          </div>

          <div className="rv-masonry">
            {visible.map((c) => (
              <figure key={c.id} className={`rv-card rv-${c.source}`}>
                <figcaption className="rv-head">
                  <span className="rv-src">
                    <SourceIcon source={c.source} />
                    {t(`reviews.src.${c.source}`)}
                  </span>
                  {c.source === "google" ? (
                    <span className="rv-stars" aria-label="5 stars">★★★★★</span>
                  ) : (
                    <span className="rv-real">{t("reviews.real")}</span>
                  )}
                </figcaption>
                <button type="button" className="rv-shot" onClick={() => setOpen(c)} aria-label={t("reviews.imageAlt")}>
                  <img src={`/reviews/cards/${c.file}`} width={c.w} height={c.h} alt={t("reviews.imageAlt")} loading="lazy" />
                </button>
              </figure>
            ))}
          </div>
        </>
      )}

      {images.length > 0 && (
        <div className="reviews-grid">
          {images.map((file) => (
            <img key={file} src={`/reviews/${file}`} alt={t("reviews.imageAlt")} loading="lazy" />
          ))}
        </div>
      )}

      {cards.length === 0 && images.length === 0 && <p className="state-msg">{t("reviews.empty")}</p>}

      {open && (
        <div className="rv-lightbox" role="dialog" aria-modal="true" onClick={() => setOpen(null)}>
          <button type="button" className="rv-close" aria-label={t("reviews.close")} onClick={() => setOpen(null)}>×</button>
          <img src={`/reviews/cards/${open.file}`} alt={t("reviews.imageAlt")} onClick={(e) => e.stopPropagation()} />
        </div>
      )}

      <div className="contact-form reviews-form">
        <h2>{t("reviews.formTitle")}</h2>
        {status === "sent" ? (
          <p className="state-msg" style={{ padding: 0 }}>{t("reviews.success")}</p>
        ) : (
          <form onSubmit={onSubmit}>
            <div className="field" style={{ marginBottom: 16 }}>
              <label><i>*</i>{t("reviews.nameLabel")}</label>
              <input required value={form.name} onChange={(e) => setField("name", e.target.value)} />
            </div>
            <div className="field" style={{ marginBottom: 16 }}>
              <label><i>*</i>{t("reviews.messageLabel")}</label>
              <textarea required value={form.message} onChange={(e) => setField("message", e.target.value)} />
            </div>
            {status === "error" && <p className="state-msg error" style={{ padding: 0 }}>{t("reviews.errorMsg")}</p>}
            <button className="btn btn-rose" style={{ width: "100%" }} type="submit" disabled={status === "sending"}>
              {status === "sending" ? t("reviews.sending") : t("reviews.submit")}
            </button>
          </form>
        )}
      </div>
    </div>
  );
}
