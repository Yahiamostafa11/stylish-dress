import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { fetchProducts } from "../api/client";
import ProductCard from "../components/ProductCard";
import BrandStory from "../components/BrandStory";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

export default function Home() {
  const { t, lang } = useLanguage();
  const [products, setProducts] = useState([]);
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    let cancelled = false;
    fetchProducts({ limit: 8 })
      .then((data) => {
        if (!cancelled) {
          setProducts(data);
          setStatus("ready");
        }
      })
      .catch(() => {
        if (!cancelled) setStatus("error");
      });
    return () => {
      cancelled = true;
    };
  }, [lang]);

  return (
    <div className="wrap">
      <Reveal as="section" className="hero">
        <div className="hero-main">
          <div className="hero-fig">
            <img src="/generated/hero-dress.webp" alt="" />
          </div>
          <div className="hero-copy">
            <h1 className="hero-h">{t("home.heroTitle")}</h1>
            <p className="hero-p">{t("home.heroSubtitle")}</p>
            <Link className="btn btn-rose" to="/shop">{t("home.heroCta")}
              <svg className="dir-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2.1" strokeLinecap="round" strokeLinejoin="round"><path d="M19 12H5M11 18l-6-6 6-6" /></svg>
            </Link>
          </div>
          {t("home.heroFoot") && <div className="hero-foot">{t("home.heroFoot")}</div>}
          <div className="dots"><b className="on"></b><b></b><b></b></div>
        </div>

        <div className="hero-side">
          <img src="/generated/hero-model.webp" alt="" />
          <p>{t("home.heroSide")}</p>
        </div>
      </Reveal>

      <section className="svc">
        <Reveal className="card c1" delay={0}>
          <div className="card-copy">
            <h3>{t("home.svc1Title")}</h3>
            <p>{t("home.svc1Desc")}</p>
            <Link className="btn btn-rose" to="/shop">{t("home.svc1Cta")}</Link>
          </div>
          <div className="card-photo">
            <img src="/generated/card-ready-to-ship.webp" alt="" />
          </div>
        </Reveal>

        <Reveal className="card c2" delay={0.08}>
          <div className="card-copy">
            <h3>{t("home.svc2Title")}</h3>
            <p>{t("home.svc2Desc")}</p>
            <Link className="btn btn-rose" to="/rent">{t("home.svc2Cta")}</Link>
          </div>
          <div className="card-photo">
            <img src="/generated/card-rent.webp" alt="" />
          </div>
        </Reveal>

        <Reveal className="card c3" delay={0.16}>
          <div className="card-copy">
            <h3>{t("home.svc3Title")}</h3>
            <p>{t("home.svc3Desc")}</p>
            <Link className="btn btn-rose" to="/marketplace">{t("home.svc3Cta")}</Link>
          </div>
          <div className="card-photo">
            <img src="/generated/card-preloved.webp" alt="" />
          </div>
        </Reveal>

        <Reveal className="card c4" delay={0.24}>
          <div className="card-copy">
            <svg className="icon" viewBox="0 0 48 48" fill="none" stroke="#3F7160" strokeWidth="2.1" strokeLinecap="round" strokeLinejoin="round">
              <path d="M24 9a3 3 0 1 1 2.2 2.9v2.6l12.5 8.6c1.6 1.1 2.6 2.9 2.6 4.9v6.4a2.6 2.6 0 0 1-2.6 2.6H9.3a2.6 2.6 0 0 1-2.6-2.6V28c0-2 1-3.8 2.6-4.9L21.8 14.5" />
              <circle cx="38" cy="14" r="6.4" fill="#E2EFE8" /><path d="M38 11.2v5.6M35.2 14h5.6" />
            </svg>
            <h3>{t("home.svc4Title")}</h3>
            <p>{t("home.svc4Desc")}</p>
            <Link className="btn btn-mint" to="/sell">{t("home.svc4Cta")}</Link>
          </div>
        </Reveal>
      </section>

      <BrandStory />

      <Reveal as="div" className="sec">
        <Link className="more" to="/shop">{t("home.viewAll")}</Link>
        <h2>{t("home.latestTitle")}
          <svg width="15" height="15" viewBox="0 0 24 24" fill="#BA5D70"><path d="M19 14c1.5-1.5 2.5-3.2 2.5-5A5 5 0 0 0 12 6.5 5 5 0 0 0 2.5 9c0 1.8 1 3.5 2.5 5l7 7z" /></svg>
        </h2>
        <span className="spacer"></span>
      </Reveal>

      {status === "loading" && <p style={{ textAlign: "center", color: "var(--muted-500)" }}>{t("home.loading")}</p>}
      {status === "error" && <p style={{ textAlign: "center", color: "var(--rose-600)" }}>{t("home.error")}</p>}

      {status === "ready" && (
        <section className="grid">
          {products.map((p, i) => (
            <Reveal key={p.id} delay={0.06 * (i % 4)}>
              <ProductCard product={p} />
            </Reveal>
          ))}
        </section>
      )}
    </div>
  );
}
