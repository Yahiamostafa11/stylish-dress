import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { fetchProduct } from "../api/client";
import { useFavorites } from "../context/FavoritesContext";
import { useLanguage } from "../context/LanguageContext";
import DressGlyph from "../components/DressGlyph";

export default function ProductDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { t } = useLanguage();
  const { isFavorite, toggleFavorite } = useFavorites();

  const [product, setProduct] = useState(null);
  const [status, setStatus] = useState("loading");
  const [activeImage, setActiveImage] = useState(0);

  useEffect(() => {
    let cancelled = false;
    setStatus("loading");
    fetchProduct(id)
      .then((data) => {
        if (!cancelled) {
          setProduct(data);
          setActiveImage(0);
          setStatus("ready");
        }
      })
      .catch(() => {
        if (!cancelled) setStatus("error");
      });
    return () => {
      cancelled = true;
    };
  }, [id]);

  if (status === "loading") {
    return <div className="wrap page"><p className="state-msg">{t("product.detailLoading")}</p></div>;
  }
  if (status === "error" || !product) {
    return <div className="wrap page"><p className="state-msg error">{t("product.detailError")}</p></div>;
  }

  const images = [product.image, ...(product.gallery || [])].filter(Boolean);
  const favored = isFavorite(product.id);

  return (
    <div className="wrap page">
      <div className="contact-grid">
        <div>
          <div className="item-thumb" style={{ width: "100%", height: 460, marginBottom: 10 }}>
            {images[activeImage] ? (
              <img src={images[activeImage]} alt={product.name} style={{ width: "100%", height: "100%", objectFit: "contain" }} />
            ) : (
              <DressGlyph width={140} height={200} />
            )}
          </div>
          {images.length > 1 && (
            <div style={{ display: "flex", gap: 8, flexWrap: "wrap" }}>
              {images.map((img, i) => (
                <button
                  key={img}
                  onClick={() => setActiveImage(i)}
                  style={{
                    width: 64, height: 64, borderRadius: 8, overflow: "hidden", padding: 0,
                    border: i === activeImage ? "2px solid var(--rose-600)" : "1px solid var(--line)",
                    cursor: "pointer", background: "#fff",
                  }}
                >
                  <img src={img} alt="" style={{ width: "100%", height: "100%", objectFit: "cover" }} />
                </button>
              ))}
            </div>
          )}
        </div>

        <div>
          {product.on_sale && <span className="story-eyebrow">{t("product.onSale")}</span>}
          <h1 style={{ fontSize: 26, color: "var(--ink-900)", margin: "6px 0 10px" }}>{product.name}</h1>

          <p style={{ display: "flex", alignItems: "baseline", gap: 10, margin: "0 0 16px" }}>
            <strong style={{ fontSize: 22, fontWeight: 700, color: "var(--rose-600)" }}>
              {product.price} {t("amnahi.currency")}
            </strong>
            {product.on_sale && product.regular_price != null && (
              <span style={{ fontSize: 14, color: "var(--muted-500)", textDecoration: "line-through" }}>
                {product.regular_price} {t("amnahi.currency")}
              </span>
            )}
          </p>

          <p className="page-sub" style={{ textAlign: "start", marginBottom: 24 }}>
            {product.description || product.short_description}
          </p>

          <div style={{ display: "flex", gap: 10 }}>
            <button className="btn btn-rose" style={{ flex: 1 }} onClick={() => navigate(`/order/${product.id}/measurements`)}>
              {t("product.startOrder")}
            </button>
            <button
              className={`fav${favored ? " fav-on" : ""}`}
              style={{ position: "static", width: 46, height: 46 }}
              aria-label={favored ? t("product.removeFav") : t("product.addFav")}
              onClick={() => toggleFavorite(product.id)}
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill={favored ? "#BA5D70" : "none"} stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.5-1.5 2.5-3.2 2.5-5A5 5 0 0 0 12 6.5 5 5 0 0 0 2.5 9c0 1.8 1 3.5 2.5 5l7 7z" /></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
