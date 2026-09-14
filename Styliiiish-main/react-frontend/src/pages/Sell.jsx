import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { createAmnahiListing } from "../api/client";
import RequireAuth from "../components/RequireAuth";
import { useLanguage } from "../context/LanguageContext";

const MAX_IMAGES = 2;
const MAX_IMAGE_MB = 5;

function SellForm() {
  const navigate = useNavigate();
  const { t } = useLanguage();
  const [form, setForm] = useState({ title: "", description: "", price: "", dressType: "" });
  const [images, setImages] = useState([]);
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const onPickImages = (e) => {
    const files = Array.from(e.target.files || []);
    if (files.length === 0) return;

    const combined = [...images, ...files].slice(0, MAX_IMAGES);
    const tooBig = combined.find((f) => f.size > MAX_IMAGE_MB * 1024 * 1024);
    if (tooBig) {
      setError(`"${tooBig.name}" > ${MAX_IMAGE_MB} ${t("sell.errorTooBig")}`);
      return;
    }
    setError("");
    setImages(combined);
    e.target.value = "";
  };

  const removeImage = (index) => setImages((imgs) => imgs.filter((_, i) => i !== index));

  const onSubmit = async (e) => {
    e.preventDefault();
    setError("");
    if (images.length === 0) {
      setError(t("sell.errorNoImage"));
      return;
    }
    setBusy(true);
    try {
      const listing = await createAmnahiListing({
        title: form.title,
        description: form.description,
        price: form.price,
        dressType: form.dressType,
        images,
      });
      navigate(`/amnahi/${listing.id}`);
    } catch (err) {
      setError(err.message || t("sell.errorGeneric"));
    } finally {
      setBusy(false);
    }
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("sell.eyebrow")}</span>
        <h1>{t("sell.title")}</h1>
        <p className="page-sub">{t("sell.subtitle")}</p>
      </div>

      <form className="order-card" onSubmit={onSubmit}>
        <div className="field" style={{ marginBottom: 18 }}>
          <label><i>*</i>{t("sell.imagesLabel")}</label>
          <div style={{ display: "flex", gap: 12, flexWrap: "wrap", marginTop: 8 }}>
            {images.map((file, i) => (
              <div key={i} style={{ position: "relative", width: 96, height: 96 }}>
                <img
                  src={URL.createObjectURL(file)}
                  alt=""
                  style={{ width: "100%", height: "100%", objectFit: "cover", borderRadius: 10, border: "1px solid var(--line)" }}
                />
                <button
                  type="button"
                  onClick={() => removeImage(i)}
                  aria-label={t("sell.removeImage")}
                  style={{
                    position: "absolute", top: -6, insetInlineEnd: -6, width: 22, height: 22, borderRadius: "50%",
                    background: "var(--rose-600)", color: "#fff", border: "2px solid #fff", cursor: "pointer", fontSize: 12,
                  }}
                >
                  ×
                </button>
              </div>
            ))}
            {images.length < MAX_IMAGES && (
              <label
                style={{
                  width: 96, height: 96, borderRadius: 10, border: "1px dashed var(--line)",
                  display: "flex", alignItems: "center", justifyContent: "center", cursor: "pointer",
                  color: "var(--muted-500)", fontSize: 24, background: "var(--surface)",
                }}
              >
                +
                <input type="file" accept="image/png,image/jpeg,image/webp" multiple hidden onChange={onPickImages} />
              </label>
            )}
          </div>
        </div>

        <div className="field-grid">
          <div className="field">
            <label><i>*</i>{t("sell.titleLabel")}</label>
            <input required value={form.title} onChange={(e) => setField("title", e.target.value)} placeholder={t("sell.titlePlaceholder")} />
          </div>
          <div className="field">
            <label><i>*</i>{t("sell.priceLabel")}</label>
            <input required type="number" min="0" value={form.price} onChange={(e) => setField("price", e.target.value)} placeholder={t("sell.pricePlaceholder")} />
          </div>
        </div>

        <div className="field" style={{ marginBottom: 18 }}>
          <label>{t("sell.typeLabel")}</label>
          <input value={form.dressType} onChange={(e) => setField("dressType", e.target.value)} placeholder={t("sell.typePlaceholder")} />
        </div>

        <div className="field">
          <label>{t("sell.descLabel")}</label>
          <textarea value={form.description} onChange={(e) => setField("description", e.target.value)} placeholder={t("sell.descPlaceholder")} />
        </div>

        {error && <p style={{ color: "var(--rose-600)", fontSize: 12.5, margin: "12px 0 0" }}>{error}</p>}

        <button className="btn btn-rose" style={{ width: "100%", marginTop: 16 }} type="submit" disabled={busy}>
          {busy ? t("sell.submitting") : t("sell.submit")}
        </button>
      </form>
    </div>
  );
}

export default function Sell() {
  return (
    <RequireAuth>
      <SellForm />
    </RequireAuth>
  );
}
