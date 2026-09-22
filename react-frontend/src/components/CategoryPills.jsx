import { useEffect, useState } from "react";
import { fetchCategories } from "../api/client";
import { useLanguage } from "../context/LanguageContext";

// Real WooCommerce product_cat terms don't carry a translated label the
// storefront can reuse directly (their `name` is the English admin label),
// so each slug maps to a translation key here. A slug with no mapping falls
// back to its raw WordPress name rather than disappearing.
export const CATEGORY_LABEL_KEYS = {
  dress: "category.dress",
  "large-size-dresses": "category.largeSizeDresses",
  "final-clearance-dresses": "category.finalClearanceDresses",
  "evening-dresses": "category.eveningDresses",
  "bridesmaids-dresses": "category.bridesmaidsDresses",
  "mothers-dresses": "category.mothersDresses",
  "wedding-dress": "category.weddingDress",
  "engagement-dresses": "category.engagementDresses",
  "bridal-party-dresses": "category.bridalPartyDresses",
  "pre-engagement-dresses": "category.preEngagementDresses",
  "graduation-dresses": "category.graduationDresses",
  "pregnant-womens-dresses": "category.pregnantWomensDresses",
  "custom-made-dresses": "category.customMadeDresses",
};

export default function CategoryPills({ activeSlug, onSelect }) {
  const { t } = useLanguage();
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    let cancelled = false;
    fetchCategories()
      .then((data) => {
        if (!cancelled) setCategories(data);
      })
      .catch(() => {
        if (!cancelled) setCategories([]);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  if (categories.length === 0) return null;

  return (
    <div className="cat-pills">
      <button className={`cat-pill${!activeSlug ? " on" : ""}`} onClick={() => onSelect("")}>
        {t("category.all")}
      </button>
      {categories.map((c) => (
        <button
          key={c.slug}
          className={`cat-pill${activeSlug === c.slug ? " on" : ""}`}
          onClick={() => onSelect(c.slug)}
        >
          {CATEGORY_LABEL_KEYS[c.slug] ? t(CATEGORY_LABEL_KEYS[c.slug]) : c.name}
        </button>
      ))}
    </div>
  );
}
