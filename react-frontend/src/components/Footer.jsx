import { Link } from "react-router-dom";
import { useLanguage } from "../context/LanguageContext";

const EXPLORE_LINKS = [
  { href: "/shop", key: "nav.shop" },
  { href: "/rent", key: "nav.rent" },
  { href: "/marketplace", key: "nav.marketplace" },
  { href: "/sell", key: "nav.sell" },
];

const COMPANY_LINKS = [
  { href: "/about", key: "nav.about" },
  { href: "/contact", key: "nav.contact" },
  { href: "/reviews", key: "nav.reviews" },
  { href: "/wishlist", key: "header.wishlist" },
  { href: "/login", key: "header.login" },
];

const LEGAL_LINKS = [
  { href: "/privacy-policy", key: "footer.privacyPolicy" },
  { href: "/terms-conditions", key: "footer.termsConditions" },
  { href: "/refund-policy", key: "footer.refundPolicy" },
  { href: "/shipping-policy", key: "footer.shippingPolicy" },
  { href: "/cookie-policy", key: "footer.cookiePolicy" },
  { href: "/marketplace-policy", key: "footer.marketplacePolicy" },
  { href: "/faq", key: "footer.faq" },
];

export default function Footer() {
  const { t } = useLanguage();
  const year = new Date().getFullYear();

  return (
    <footer className="site-footer">
      <div className="wrap footer-top">
        <div className="footer-brand">
          <Link to="/" className="brand">
            <svg className="mono" viewBox="0 0 40 44" fill="none">
              <path d="M28 7c-4-3-11-3-14 1-3 3.4-2 8 2 10.4l7 4.2c4 2.4 5 7 2 10.4-3 4-10 4-14 1" stroke="#BA5D70" strokeWidth="3.4" strokeLinecap="round" />
              <path d="M11 37c4 3 11 3 14-1 3-3.4 2-8-2-10.4l-7-4.2C12 19 11 14.4 14 11c3-4 10-4 14-1" stroke="#3F7160" strokeWidth="3.4" strokeLinecap="round" opacity=".85" />
            </svg>
            <div className="brand-txt">
              <div className="brand-name">Styliiiish</div>
            </div>
          </Link>
          <p className="footer-blurb">{t("footer.blurb")}</p>
        </div>

        <div className="footer-col">
          <h5>{t("footer.exploreTitle")}</h5>
          <ul>
            {EXPLORE_LINKS.map((item) => (
              <li key={item.href}><Link to={item.href}>{t(item.key)}</Link></li>
            ))}
          </ul>
        </div>

        <div className="footer-col">
          <h5>{t("footer.companyTitle")}</h5>
          <ul>
            {COMPANY_LINKS.map((item) => (
              <li key={item.href}><Link to={item.href}>{t(item.key)}</Link></li>
            ))}
          </ul>
        </div>

        <div className="footer-col">
          <h5>{t("footer.contactTitle")}</h5>
          <ul>
            <li><a href="mailto:hello@styliiiish.com">hello@styliiiish.com</a></li>
            <li><Link to="/contact">{t("nav.contact")}</Link></li>
          </ul>
        </div>

        <div className="footer-col">
          <h5>{t("footer.legalTitle")}</h5>
          <ul>
            {LEGAL_LINKS.map((item) => (
              <li key={item.href}><Link to={item.href}>{t(item.key)}</Link></li>
            ))}
          </ul>
        </div>
      </div>

      <div className="wrap footer-bottom">
        <span>© {year} Styliiiish. {t("footer.rights")}</span>
        <span className="footer-note">{t("footer.note")}</span>
      </div>
    </footer>
  );
}
