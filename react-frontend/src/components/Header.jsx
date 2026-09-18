import { useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useFavorites } from "../context/FavoritesContext";
import { useCart } from "../context/CartContext";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";

const NAV = [
  { href: "/", key: "nav.home" },
  { href: "/shop", key: "nav.shop" },
  { href: "/rent", key: "nav.rent" },
  { href: "/marketplace", key: "nav.marketplace" },
  { href: "/amnahi", key: "nav.amnahi" },
  { href: "/sell", key: "nav.sell" },
  { href: "/about", key: "nav.about" },
  { href: "/contact", key: "nav.contact" },
  { href: "/reviews", key: "nav.reviews" },
];

export default function Header() {
  const { pathname } = useLocation();
  const navigate = useNavigate();
  const { favorites } = useFavorites();
  const { count: cartCount } = useCart();
  const { isAuthed, user, logout } = useAuth();
  const { t, lang, toggleLang } = useLanguage();
  const [search, setSearch] = useState("");

  const submitSearch = (e) => {
    e.preventDefault();
    const q = search.trim();
    navigate(q ? `/shop?search=${encodeURIComponent(q)}` : "/shop");
  };

  const onLogout = () => {
    logout();
    navigate("/");
  };

  return (
    <header className="site">
      <div className="wrap bar">
        <Link to="/" className="brand">
          <svg className="mono" viewBox="0 0 40 44" fill="none">
            <path d="M28 7c-4-3-11-3-14 1-3 3.4-2 8 2 10.4l7 4.2c4 2.4 5 7 2 10.4-3 4-10 4-14 1" stroke="#BA5D70" strokeWidth="3.4" strokeLinecap="round" />
            <path d="M11 37c4 3 11 3 14-1 3-3.4 2-8-2-10.4l-7-4.2C12 19 11 14.4 14 11c3-4 10-4 14-1" stroke="#3F7160" strokeWidth="3.4" strokeLinecap="round" opacity=".85" />
          </svg>
          <div className="brand-txt">
            <div className="brand-name">Styliiiish</div>
            <div className="brand-tag">{lang === "ar" ? "أزياء تحكي قصتك" : "Fashion that tells your story"}</div>
          </div>
        </Link>

        <form className="search" onSubmit={submitSearch}>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C4B8B4" strokeWidth="2" strokeLinecap="round"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
          <input
            type="text"
            placeholder={t("header.searchPlaceholder")}
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </form>

        <nav className="main">
          {NAV.map((item) => (
            <Link key={item.href} to={item.href} className={pathname === item.href ? "on" : ""}>
              {t(item.key)}
            </Link>
          ))}
        </nav>

        <div className="icons">
          <button type="button" onClick={toggleLang} className="lang-toggle" aria-label={t("header.langToggle")}>
            {t("header.langToggle")}
          </button>

          <Link to={isAuthed ? "/messages" : "/login"} className="badge" aria-label={t("header.messages")}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C4A48" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" /></svg>
          </Link>

          <Link to="/wishlist" className="badge" aria-label={t("header.wishlist")}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C4A48" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round"><path d="M19 14c1.5-1.5 2.5-3.2 2.5-5A5 5 0 0 0 12 6.5 5 5 0 0 0 2.5 9c0 1.8 1 3.5 2.5 5l7 7z" /></svg>
            {favorites.length > 0 && <i>{favorites.length}</i>}
          </Link>

          {isAuthed ? (
            <button onClick={onLogout} className="account-btn" title={`${t("header.logout")} (${user?.name ?? ""})`} aria-label={t("header.logout")}>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C4A48" strokeWidth="1.7" strokeLinecap="round"><circle cx="12" cy="8" r="3.6" /><path d="M4.5 20a7.5 7.5 0 0 1 15 0" /></svg>
            </button>
          ) : (
            <Link to="/login" aria-label={t("header.login")}>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C4A48" strokeWidth="1.7" strokeLinecap="round"><circle cx="12" cy="8" r="3.6" /><path d="M4.5 20a7.5 7.5 0 0 1 15 0" /></svg>
            </Link>
          )}

          <Link to="/cart" className="badge" aria-label={t("header.cart")}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5C4A48" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round"><path d="M6 7h12l1.2 13H4.8z" /><path d="M9 7V5a3 3 0 0 1 6 0v2" /></svg>
            {cartCount > 0 && <i>{cartCount}</i>}
          </Link>
        </div>
      </div>
    </header>
  );
}
