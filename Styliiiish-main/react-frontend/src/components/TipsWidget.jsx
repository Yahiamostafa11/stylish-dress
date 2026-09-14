import { useState } from "react";
import { useLanguage } from "../context/LanguageContext";

const TIP_KEYS = [
  { cats: ["tips.0.cat0", "tips.0.cat1", "tips.0.cat2", "tips.0.cat3"], text: "tips.0.text" },
  { cats: ["tips.1.cat0", "tips.1.cat1"], text: "tips.1.text" },
  { cats: ["tips.2.cat0", "tips.2.cat1"], text: "tips.2.text" },
  { cats: ["tips.3.cat0", "tips.3.cat1"], text: "tips.3.text" },
  { cats: ["tips.4.cat0", "tips.4.cat1"], text: "tips.4.text" },
];

export default function TipsWidget() {
  const { t } = useLanguage();
  const [open, setOpen] = useState(true);
  const [index, setIndex] = useState(0);
  const tip = TIP_KEYS[index];

  if (!open) {
    return (
      <button
        className="tips-bubble"
        onClick={() => setOpen(true)}
        aria-label={t("tips.open")}
      >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="m12 3 1.9 4.6L18.5 9l-4.6 1.4L12 15l-1.9-4.6L5.5 9l4.6-1.4z" /></svg>
      </button>
    );
  }

  return (
    <aside className="tips">
      <div className="tips-top">
        <strong>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="#3F7160"><path d="m12 3 1.9 4.6L18.5 9l-4.6 1.4L12 15l-1.9-4.6L5.5 9l4.6-1.4z" /></svg>
          {t("tips.title")}
        </strong>
        <button onClick={() => setOpen(false)} aria-label={t("tips.close")}>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B9AFAB" strokeWidth="2.2" strokeLinecap="round"><path d="M18 6 6 18M6 6l12 12" /></svg>
        </button>
      </div>
      <div className="tips-cats">
        <b>{t(tip.cats[0])}</b>
        {tip.cats.slice(1).map((c) => (
          <span key={c}>• {t(c)}</span>
        ))}
      </div>
      <p>{t(tip.text)}</p>
      <div className="next" onClick={() => setIndex((i) => (i + 1) % TIP_KEYS.length)}>
        {t("tips.next")}
      </div>
    </aside>
  );
}
