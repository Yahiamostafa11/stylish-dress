import { useEffect, useRef, useState } from "react";
import { useLanguage } from "../context/LanguageContext";

const STAT_ICONS = [
  <svg key="0" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="9" /><path d="M3 12h18M12 3c2.5 2.5 3.8 5.7 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.7-3.8-9S9.5 5.5 12 3z" /></svg>,
  <svg key="1" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3F7160" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="8" width="18" height="8" rx="2" /><path d="M7 8v3M11 8v3M15 8v3" /></svg>,
  <svg key="2" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.5-1.5 2.5-3.2 2.5-5A5 5 0 0 0 12 6.5 5 5 0 0 0 2.5 9c0 1.8 1 3.5 2.5 5l7 7z" /></svg>,
];

const STAT_KEYS = ["story.stat0", "story.stat1", "story.stat2"];

export default function BrandStory() {
  const { t } = useLanguage();
  const ref = useRef(null);
  const [inView, setInView] = useState(false);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setInView(true);
          observer.disconnect();
        }
      },
      { threshold: 0.25 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, []);

  return (
    <section className={`story${inView ? " in-view" : ""}`} ref={ref}>
      <div className="story-fig">
        <div className="story-fig-backdrop" />
        <svg width="120" height="150" viewBox="0 0 112 190" fill="none" className="story-dress">
          <path d="M56 8c-7 0-12 5-12 11 0 5 3 8 3 12l-6 8c-4 5-7 12-9 20l-12 56c-2 9-6 30-6 30s18 10 42 10 42-10 42-10-4-21-6-30l-12-56c-2-8-5-15-9-20l-6-8c0-4 3-7 3-12 0-6-5-11-12-11z" fill="#fff" fillOpacity=".85" stroke="#BA5D70" strokeWidth="1.6" />
        </svg>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="#C06376" className="story-spark story-spark-1"><path d="m12 3 1.9 4.6L18.5 9l-4.6 1.4L12 15l-1.9-4.6L5.5 9l4.6-1.4z" /></svg>
        <svg width="11" height="11" viewBox="0 0 24 24" fill="#3F7160" className="story-spark story-spark-2"><path d="m12 3 1.9 4.6L18.5 9l-4.6 1.4L12 15l-1.9-4.6L5.5 9l4.6-1.4z" /></svg>
      </div>

      <div className="story-copy">
        {t("story.eyebrow") && <span className="story-eyebrow">{t("story.eyebrow")}</span>}
        <h2>{t("story.title")}</h2>
        <p className="story-lead">{t("story.lead")}</p>
        <p>{t("story.body")}</p>
        <p>{t("story.body2")}</p>

        <div className="story-stats">
          {STAT_KEYS.map((key, i) => (
            <div className="story-stat" key={key} style={{ transitionDelay: `${0.12 * (i + 1)}s` }}>
              <span className="story-stat-icon">{STAT_ICONS[i]}</span>
              {t(key)}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
