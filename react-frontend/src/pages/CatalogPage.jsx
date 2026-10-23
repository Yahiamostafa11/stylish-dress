import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";
import { fetchProducts } from "../api/client";
import ProductCard from "../components/ProductCard";
import CategoryPills from "../components/CategoryPills";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

export default function CatalogPage({ eyebrowKey, titleKey, subtitleKey, limit = 60, showCategories = false }) {
  const { t, lang } = useLanguage();
  const [params, setParams] = useSearchParams();
  const urlSearch = params.get("search") || "";
  const urlCategory = params.get("category") || "";
  const urlSize = params.get("size") || "";
  const urlMinPrice = params.get("min_price") || "";
  const urlMaxPrice = params.get("max_price") || "";
  const hasDiscoveryFilters = Boolean(urlSize || urlMinPrice || urlMaxPrice);
  const [search, setSearch] = useState(urlSearch);
  const [products, setProducts] = useState([]);
  const [status, setStatus] = useState("loading");
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);

  useEffect(() => {
    setSearch(urlSearch);
  }, [urlSearch]);

  useEffect(() => {
    let cancelled = false;
    setStatus("loading");
    setPage(1);
    fetchProducts({ search: urlSearch, category: urlCategory, size: urlSize, minPrice: urlMinPrice, maxPrice: urlMaxPrice, limit, page: 1 })
      .then(({ items, meta }) => {
        if (!cancelled) {
          setProducts(items);
          setHasMore(Boolean(meta?.has_more));
          setStatus("ready");
        }
      })
      .catch(() => {
        if (!cancelled) setStatus("error");
      });
    return () => {
      cancelled = true;
    };
  }, [urlSearch, urlCategory, urlSize, urlMinPrice, urlMaxPrice, limit, lang]);

  const loadMore = () => {
    const next = page + 1;
    setLoadingMore(true);
    fetchProducts({ search: urlSearch, category: urlCategory, size: urlSize, minPrice: urlMinPrice, maxPrice: urlMaxPrice, limit, page: next })
      .then(({ items, meta }) => {
        setProducts((prev) => {
          const seen = new Set(prev.map((p) => p.id));
          return [...prev, ...items.filter((p) => !seen.has(p.id))];
        });
        setPage(next);
        setHasMore(Boolean(meta?.has_more));
      })
      .catch(() => {})
      .finally(() => setLoadingMore(false));
  };

  const discoveryParams = () => {
    const next = {};
    if (urlSize) next.size = urlSize;
    if (urlMinPrice) next.min_price = urlMinPrice;
    if (urlMaxPrice) next.max_price = urlMaxPrice;
    return next;
  };

  const onSubmit = (e) => {
    e.preventDefault();
    const q = search.trim();
    const next = discoveryParams();
    if (q) next.search = q;
    if (urlCategory) next.category = urlCategory;
    setParams(next);
  };

  const onSelectCategory = (slug) => {
    const next = discoveryParams();
    if (urlSearch) next.search = urlSearch;
    if (slug) next.category = slug;
    setParams(next);
  };

  const clearDiscoveryFilters = () => {
    const next = {};
    if (urlSearch) next.search = urlSearch;
    if (urlCategory) next.category = urlCategory;
    setParams(next);
  };

  return (
    <div className="wrap page">
      <Reveal as="div" className="page-head">
        <span className="story-eyebrow">{t(eyebrowKey)}</span>
        <h1>{t(titleKey)}</h1>
        {subtitleKey && <p className="page-sub">{t(subtitleKey)}</p>}
        <form className="page-search" onSubmit={onSubmit}>
          <input
            type="text"
            placeholder={t("catalog.searchPlaceholder")}
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
          <button className="btn btn-rose" type="submit">{t("catalog.search")}</button>
        </form>
      </Reveal>

      {showCategories && <CategoryPills activeSlug={urlCategory} onSelect={onSelectCategory} />}

      {hasDiscoveryFilters && (
        <p className="discovery-active">
          {t("discovery.filtersActive")}
          <button type="button" onClick={clearDiscoveryFilters}>{t("discovery.clear")}</button>
        </p>
      )}

      {status === "loading" && <p className="state-msg">{t("catalog.loading")}</p>}
      {status === "error" && <p className="state-msg error">{t("catalog.error")}</p>}
      {status === "ready" && products.length === 0 && (
        <p className="state-msg">{t("catalog.empty")}</p>
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

      {status === "ready" && hasMore && (
        <div style={{ textAlign: "center", margin: "8px 0 40px" }}>
          <button className="btn btn-rose" type="button" onClick={loadMore} disabled={loadingMore}>
            {loadingMore ? t("catalog.loading") : t("catalog.loadMore")}
          </button>
        </div>
      )}
    </div>
  );
}
