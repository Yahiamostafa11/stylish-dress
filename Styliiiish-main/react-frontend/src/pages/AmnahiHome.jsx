import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { fetchAmnahiListings } from "../api/client";
import AmnahiCard from "../components/AmnahiCard";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

export default function AmnahiHome() {
  const { t } = useLanguage();
  const [listings, setListings] = useState([]);
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    let cancelled = false;
    fetchAmnahiListings({ limit: 40 })
      .then((data) => {
        if (!cancelled) {
          setListings(data);
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
      <Reveal as="div" className="page-head">
        <span className="story-eyebrow">{t("amnahi.eyebrow")}</span>
        <h1>{t("amnahi.title")}</h1>
        <p className="page-sub">{t("amnahi.subtitle")}</p>
        <Link className="btn btn-mint" to="/sell">{t("amnahi.sellCta")}</Link>
      </Reveal>

      {status === "loading" && <p className="state-msg">{t("amnahi.loading")}</p>}
      {status === "error" && <p className="state-msg error">{t("amnahi.error")}</p>}
      {status === "ready" && listings.length === 0 && (
        <p className="state-msg">{t("amnahi.empty")}</p>
      )}

      {status === "ready" && listings.length > 0 && (
        <section className="grid">
          {listings.map((l, i) => (
            <Reveal key={l.id} delay={0.05 * (i % 4)}>
              <AmnahiCard listing={l} />
            </Reveal>
          ))}
        </section>
      )}
    </div>
  );
}
