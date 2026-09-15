import { Link, useNavigate } from "react-router-dom";
import DressGlyph from "./DressGlyph";
import { useFavorites } from "../context/FavoritesContext";
import { useLanguage } from "../context/LanguageContext";

const FALLBACK_GRADIENT = "linear-gradient(200deg,#EFE2E6,#DCCCD4)";

export default function ProductCard({ product }) {
  const navigate = useNavigate();
  const { t } = useLanguage();
  const { isFavorite, toggleFavorite } = useFavorites();
  const favored = isFavorite(product.id);
  const goToProduct = () => navigate(`/product/${product.id}`);

  return (
    <article className="p">
      <div
        className="p-img"
        style={{ background: product.image ? undefined : FALLBACK_GRADIENT, cursor: "pointer" }}
        onClick={goToProduct}
        role="button"
        tabIndex={0}
        onKeyDown={(e) => (e.key === "Enter" || e.key === " ") && goToProduct()}
      >
        {product.on_sale && <span className="tag">{t("product.onSale")}</span>}
        <button
          className={`fav${favored ? " fav-on" : ""}`}
          aria-label={favored ? t("product.removeFav") : t("product.addFav")}
          onClick={(e) => {
            e.stopPropagation();
            toggleFavorite(product.id);
          }}
        >
          <svg width="15" height="15" viewBox="0 0 24 24" fill={favored ? "#BA5D70" : "none"} stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.5-1.5 2.5-3.2 2.5-5A5 5 0 0 0 12 6.5 5 5 0 0 0 2.5 9c0 1.8 1 3.5 2.5 5l7 7z" /></svg>
        </button>
        {product.image ? (
          <img
            src={product.image}
            alt={product.name}
            loading="lazy"
            style={{ width: "100%", height: "100%", objectFit: "contain" }}
          />
        ) : (
          <DressGlyph />
        )}
      </div>
      <div className="p-body">
        <h4>{product.name}</h4>
        <p>{product.short_description || " "}</p>
        <div className="p-row">
          <Link className="link-btn" to={`/product/${product.id}`}>{t("product.viewDetails")}</Link>
        </div>
      </div>
    </article>
  );
}
