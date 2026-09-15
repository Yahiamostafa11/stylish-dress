import { Link } from "react-router-dom";
import DressGlyph from "./DressGlyph";
import { useLanguage } from "../context/LanguageContext";

export default function AmnahiCard({ listing }) {
  const { t } = useLanguage();

  return (
    <article className="p">
      <Link to={`/amnahi/${listing.id}`} className="p-img" style={{ background: listing.image ? undefined : "linear-gradient(200deg,#EFE2E6,#DCCCD4)" }}>
        {listing.image ? (
          <img src={listing.image} alt={listing.name} loading="lazy" style={{ width: "100%", height: "100%", objectFit: "contain" }} />
        ) : (
          <DressGlyph />
        )}
      </Link>
      <div className="p-body">
        <h4>{listing.name}</h4>
        <p>{t("amnahi.from")} {listing.seller_name || t("amnahi.defaultSeller")}</p>
        <div className="p-row">
          <strong style={{ color: "var(--rose-600)", fontSize: 14 }}>
            {listing.price ? `${listing.price} ${t("amnahi.currency")}` : ""}
          </strong>
          <Link className="link-btn" to={`/amnahi/${listing.id}`}>{t("product.viewDetails")}</Link>
        </div>
      </div>
    </article>
  );
}
