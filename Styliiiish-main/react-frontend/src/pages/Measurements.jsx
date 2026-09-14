import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { fetchProduct } from "../api/client";
import { useLanguage } from "../context/LanguageContext";
import { useCart } from "../context/CartContext";

const FIELD_KEYS = [
  { key: "chest", labelKey: "measurements.field.chest", value: "96" },
  { key: "waist", labelKey: "measurements.field.waist", value: "78" },
  { key: "hips", labelKey: "measurements.field.hips", value: "104" },
  { key: "shoulderWidth", labelKey: "measurements.field.shoulderWidth", value: "39" },
  { key: "upperArm", labelKey: "measurements.field.upperArm", value: "31" },
  { key: "sleeveLength", labelKey: "measurements.field.sleeveLength", value: "58" },
  { key: "wrist", labelKey: "measurements.field.wrist", value: "17" },
  { key: "shoulderToWaist", labelKey: "measurements.field.shoulderToWaist", value: "42" },
  { key: "dressLength", labelKey: "measurements.field.dressLength", value: "145" },
  { key: "bodyLength", labelKey: "measurements.field.bodyLength", value: "168" },
  { key: "heelHeight", labelKey: "measurements.field.heelHeight", value: "5" },
];

const STEP_KEYS = ["measurements.step1", "measurements.step2", "measurements.step3"];

export default function Measurements() {
  const { t } = useLanguage();
  const navigate = useNavigate();
  const { addItem } = useCart();
  const { productId } = useParams();
  const [product, setProduct] = useState(null);
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    let cancelled = false;
    setStatus("loading");
    fetchProduct(productId)
      .then((data) => {
        if (!cancelled) {
          setProduct(data);
          setStatus("ready");
        }
      })
      .catch(() => {
        if (!cancelled) setStatus("error");
      });
    return () => {
      cancelled = true;
    };
  }, [productId]);

  const hasReadySizes = (product?.sizes?.length ?? 0) > 0;
  const [sizeType, setSizeType] = useState("custom");
  const [selectedVariationId, setSelectedVariationId] = useState(null);
  const [unit, setUnit] = useState("cm");
  const [values, setValues] = useState(
    Object.fromEntries(FIELD_KEYS.map((f) => [f.key, f.value]))
  );
  const [notes, setNotes] = useState("");
  const [confirmed, setConfirmed] = useState(false);

  useEffect(() => {
    if (product?.sizes?.length) {
      setSizeType("ready");
      setSelectedVariationId(product.sizes.find((s) => s.in_stock)?.variation_id ?? product.sizes[0].variation_id);
    }
  }, [product]);

  const setField = (key, val) => setValues((v) => ({ ...v, [key]: val }));
  const selectedSize = product?.sizes?.find((s) => s.variation_id === selectedVariationId);
  const canSubmit = confirmed && (sizeType === "custom" || !!selectedVariationId);

  const handleAddToCart = () => {
    const measurements =
      sizeType === "custom"
        ? Object.fromEntries(FIELD_KEYS.map((f) => [t(f.labelKey), `${values[f.key]} ${unit === "cm" ? t("measurements.unitCm") : t("measurements.unitIn")}`]))
        : { [t("measurements.sizeLabel")]: selectedSize?.label };

    addItem({
      productId: product.id,
      variationId: sizeType === "ready" ? selectedVariationId : null,
      name: product.name,
      image: product.image,
      price: sizeType === "ready" && selectedSize?.price != null ? selectedSize.price : product.price,
      measurements,
      sizeType,
      unit,
      notes,
    });
    navigate("/cart");
  };

  if (status === "loading") {
    return <div className="wrap"><p style={{ textAlign: "center", color: "var(--muted-500)" }}>{t("measurements.loading")}</p></div>;
  }

  if (status === "error" || !product) {
    return <div className="wrap"><p style={{ textAlign: "center", color: "var(--rose-600)" }}>{t("measurements.error")}</p></div>;
  }

  return (
    <div className="wrap">
      <div className="wiz-head">
        {STEP_KEYS.map((key, i) => (
          <div key={key} style={{ display: "flex", alignItems: "center", gap: 10 }}>
            <div className={`wiz-step ${i === 1 ? "active" : i === 0 ? "done" : ""}`}>
              <b>{i + 1}</b>
              {t(key)}
            </div>
            {i < STEP_KEYS.length - 1 && <span className="wiz-line" />}
          </div>
        ))}
      </div>

      <div className="order-card">
        <div className="item-card">
          <div className="item-thumb">
            {product.image && (
              <img src={product.image} alt={product.name} style={{ width: "100%", height: "100%", objectFit: "cover" }} />
            )}
          </div>
          <div className="item-info">
            <h4>{product.name}</h4>
            <p>{product.short_description || product.description?.slice(0, 80)}</p>
            <span className="tag" style={{ position: "static", display: "inline-block" }}>{t("measurements.onSale")}</span>
          </div>
        </div>

        <div className="seg">
          {hasReadySizes && (
            <button className={sizeType === "ready" ? "on" : ""} onClick={() => setSizeType("ready")}>{t("measurements.readySize")}</button>
          )}
          <button className={sizeType === "custom" ? "on" : ""} onClick={() => setSizeType("custom")}>{t("measurements.customSize")}</button>
        </div>

        {sizeType === "ready" ? (
          <div className="size-picker">
            {product.sizes.map((s) => (
              <button
                key={s.variation_id}
                type="button"
                className={`size-chip${selectedVariationId === s.variation_id ? " on" : ""}`}
                disabled={!s.in_stock}
                onClick={() => setSelectedVariationId(s.variation_id)}
              >
                {s.label}
                {!s.in_stock && <i>{t("measurements.outOfStock")}</i>}
              </button>
            ))}
          </div>
        ) : (
          <>
            <div className="unit-row">
              <span>{t("measurements.unitLabel")}</span>
              <div className="unit-toggle">
                <button className={unit === "cm" ? "on" : ""} onClick={() => setUnit("cm")}>{t("measurements.unitCm")}</button>
                <button className={unit === "in" ? "on" : ""} onClick={() => setUnit("in")}>{t("measurements.unitIn")}</button>
              </div>
            </div>

            <div className="guide-box">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#3F7160" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="8" width="18" height="8" rx="2" /><path d="M7 8v3M11 8v3M15 8v3" /></svg>
              <p>{t("measurements.guideText")}</p>
              <a href="#guide">{t("measurements.guideLink")}</a>
            </div>

            <div className="field-grid">
              {FIELD_KEYS.map((f) => (
                <div className="field" key={f.key}>
                  <label><i>*</i>{t(f.labelKey)}</label>
                  <div className="input-wrap">
                    <input
                      type="number"
                      value={values[f.key]}
                      onChange={(e) => setField(f.key, e.target.value)}
                    />
                    <span className="unit">{unit === "cm" ? t("measurements.unitCm") : t("measurements.unitIn")}</span>
                  </div>
                </div>
              ))}
            </div>
          </>
        )}

        <div className="field">
          <label>{t("measurements.notesLabel")}</label>
          <textarea value={notes} onChange={(e) => setNotes(e.target.value)} placeholder={t("measurements.notesPlaceholder")} />
        </div>

        <label className="check-row">
          <input type="checkbox" checked={confirmed} onChange={(e) => setConfirmed(e.target.checked)} />
          {t("measurements.confirmCheck")}
        </label>

        <button className="btn btn-rose" style={{ width: "100%" }} disabled={!canSubmit} onClick={handleAddToCart}>
          {t("measurements.reviewOrder")}
          <svg className="dir-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2.1" strokeLinecap="round" strokeLinejoin="round"><path d="M19 12H5M11 18l-6-6 6-6" /></svg>
        </button>

        <div className="note-box">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F7160" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="9" /><path d="M12 8h.01M11 12h1v4h1" /></svg>
          {t("measurements.demoNote")}
        </div>
      </div>
    </div>
  );
}
