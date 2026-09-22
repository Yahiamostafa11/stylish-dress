import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { fetchCategories, fetchSizes, fetchPriceRange } from "../api/client";
import { CATEGORY_LABEL_KEYS } from "./CategoryPills";
import { useLanguage } from "../context/LanguageContext";

export default function DiscoveryPopup() {
  const { t } = useLanguage();
  const navigate = useNavigate();
  const [open, setOpen] = useState(true);
  const [minimized, setMinimized] = useState(false);

  const [categories, setCategories] = useState([]);
  const [sizes, setSizes] = useState([]);
  const [priceRange, setPriceRange] = useState(null);

  const [category, setCategory] = useState("");
  const [size, setSize] = useState("");
  const [budget, setBudget] = useState(null);

  useEffect(() => {
    fetchCategories().then(setCategories).catch(() => setCategories([]));
    fetchSizes().then(setSizes).catch(() => setSizes([]));
    fetchPriceRange().then(setPriceRange).catch(() => setPriceRange(null));
  }, []);

  const budgetBuckets = (() => {
    if (!priceRange || priceRange.breakpoints.length < 2) return [];
    const [low, high] = priceRange.breakpoints;
    const egp = t("amnahi.currency");
    return [
      { key: "under", min: "", max: low, label: `${t("discovery.under")} ${low.toLocaleString()} ${egp}` },
      { key: "mid", min: low, max: high, label: `${low.toLocaleString()}–${high.toLocaleString()} ${egp}` },
      { key: "over", min: high, max: "", label: `${t("discovery.over")} ${high.toLocaleString()} ${egp}` },
    ];
  })();

  const cancel = () => {
    setOpen(false);
    setMinimized(true);
  };

  const reopen = () => {
    setMinimized(false);
    setOpen(true);
  };

  const goSell = () => {
    navigate("/sell");
    setOpen(false);
    setMinimized(true);
  };

  const showResults = () => {
    const params = new URLSearchParams();
    if (category) params.set("category", category);
    if (size) params.set("size", size);
    if (budget?.min) params.set("min_price", String(budget.min));
    if (budget?.max) params.set("max_price", String(budget.max));
    navigate(`/shop?${params.toString()}`);
    setOpen(false);
    setMinimized(true);
  };

  if (minimized) {
    return (
      <button className="discovery-bubble" onClick={reopen} aria-label={t("discovery.open")}>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
      </button>
    );
  }

  if (!open) return null;

  return (
    <div className="discovery-overlay" onClick={cancel}>
      <div className="discovery-modal" onClick={(e) => e.stopPropagation()}>
        <div className="discovery-top">
          <strong>{t("discovery.title")}</strong>
          <button onClick={cancel} aria-label={t("discovery.close")}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B9AFAB" strokeWidth="2.2" strokeLinecap="round"><path d="M18 6 6 18M6 6l12 12" /></svg>
          </button>
        </div>
        <p className="discovery-sub">{t("discovery.subtitle")}</p>

        {categories.length > 0 && (
          <div className="discovery-group">
            <h4>{t("discovery.categoryLabel")}</h4>
            <div className="discovery-pills">
              {categories.map((c) => (
                <button
                  key={c.slug}
                  type="button"
                  className={`discovery-pill${category === c.slug ? " on" : ""}`}
                  onClick={() => setCategory((cur) => (cur === c.slug ? "" : c.slug))}
                >
                  {CATEGORY_LABEL_KEYS[c.slug] ? t(CATEGORY_LABEL_KEYS[c.slug]) : c.name}
                </button>
              ))}
              <button type="button" className="discovery-pill discovery-pill-sell" onClick={goSell}>
                {t("discovery.sellYourDress")}
              </button>
            </div>
          </div>
        )}

        {budgetBuckets.length > 0 && (
          <div className="discovery-group">
            <h4>{t("discovery.budgetLabel")}</h4>
            <div className="discovery-pills">
              {budgetBuckets.map((b) => (
                <button
                  key={b.key}
                  type="button"
                  className={`discovery-pill${budget?.key === b.key ? " on" : ""}`}
                  onClick={() => setBudget((cur) => (cur?.key === b.key ? null : b))}
                >
                  {b.label}
                </button>
              ))}
            </div>
          </div>
        )}

        {sizes.length > 0 && (
          <div className="discovery-group">
            <h4>{t("discovery.sizeLabel")}</h4>
            <div className="discovery-pills">
              {sizes.map((s) => (
                <button
                  key={s.slug}
                  type="button"
                  className={`discovery-pill${size === s.slug ? " on" : ""}`}
                  onClick={() => setSize((cur) => (cur === s.slug ? "" : s.slug))}
                >
                  {s.name}
                </button>
              ))}
            </div>
          </div>
        )}

        <div className="discovery-actions">
          <button type="button" className="discovery-skip" onClick={cancel}>{t("discovery.skip")}</button>
          <button type="button" className="btn btn-rose" onClick={showResults}>{t("discovery.submit")}</button>
        </div>
      </div>
    </div>
  );
}
