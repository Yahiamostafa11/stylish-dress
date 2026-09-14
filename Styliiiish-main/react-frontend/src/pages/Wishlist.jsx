import { useEffect, useState } from "react";
import { useFavorites } from "../context/FavoritesContext";
import { fetchProduct } from "../api/client";
import ProductCard from "../components/ProductCard";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

export default function Wishlist() {
  const { t } = useLanguage();
  const { favorites } = useFavorites();
  const [products, setProducts] = useState([]);
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    let cancelled = false;

    if (favorites.length === 0) {
      setProducts([]);
      setStatus("ready");
      return;
    }

    setStatus("loading");
    Promise.all(favorites.map((id) => fetchProduct(id).catch(() => null))).then((results) => {
      if (!cancelled) {
        setProducts(results.filter(Boolean));
        setStatus("ready");
      }
    });

    return () => {
      cancelled = true;
    };
  }, [favorites]);

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("wishlist.eyebrow")}</span>
        <h1>{t("wishlist.title")}</h1>
        <p className="page-sub">{t("wishlist.subtitle")}</p>
      </div>

      {status === "loading" && <p className="state-msg">{t("wishlist.loading")}</p>}
      {status === "ready" && products.length === 0 && (
        <p className="state-msg">{t("wishlist.empty")}</p>
      )}

      {status === "ready" && products.length > 0 && (
        <section className="grid">
          {products.map((p, i) => (
            <Reveal key={p.id} delay={0.05 * (i % 4)}>
              <ProductCard product={p} />
            </Reveal>
          ))}
        </section>
      )}
    </div>
  );
}
