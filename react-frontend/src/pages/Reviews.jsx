import { useEffect, useState } from "react";
import { useLanguage } from "../context/LanguageContext";
import { submitReview } from "../api/client";

export default function Reviews() {
  const { t } = useLanguage();
  const [images, setImages] = useState([]);
  const [form, setForm] = useState({ name: "", message: "" });
  const [status, setStatus] = useState("idle");

  useEffect(() => {
    fetch("/reviews/manifest.json")
      .then((res) => (res.ok ? res.json() : []))
      .then((list) => setImages(Array.isArray(list) ? list : []))
      .catch(() => setImages([]));
  }, []);

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

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("reviews.eyebrow")}</span>
        <h1>{t("reviews.title")}</h1>
        <p className="page-sub">{t("reviews.subtitle")}</p>
      </div>

      {images.length > 0 ? (
        <div className="reviews-grid">
          {images.map((file) => (
            <img key={file} src={`/reviews/${file}`} alt={t("reviews.imageAlt")} loading="lazy" />
          ))}
        </div>
      ) : (
        <p className="state-msg">{t("reviews.empty")}</p>
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
