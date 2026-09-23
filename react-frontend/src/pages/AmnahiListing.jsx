import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { fetchAmnahiListing, expressInterest } from "../api/client";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import DressGlyph from "../components/DressGlyph";

export default function AmnahiListing() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { isAuthed, user } = useAuth();
  const { t } = useLanguage();

  const [listing, setListing] = useState(null);
  const [status, setStatus] = useState("loading");
  const [activeImage, setActiveImage] = useState(0);
  const [interestBusy, setInterestBusy] = useState(false);
  const [interestError, setInterestError] = useState("");

  useEffect(() => {
    let cancelled = false;
    setStatus("loading");
    fetchAmnahiListing(id)
      .then((data) => {
        if (!cancelled) {
          setListing(data);
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

  const onInterested = async () => {
    if (!isAuthed) {
      navigate("/login", { state: { from: `/amnahi/${id}` } });
      return;
    }
    setInterestError("");
    setInterestBusy(true);
    try {
      const res = await expressInterest(id);
      navigate(`/messages/${res.conversation_id}`);
    } catch (err) {
      setInterestError(err.message || t("amnahi.interestedError"));
    } finally {
      setInterestBusy(false);
    }
  };

  if (status === "loading") {
    return <div className="wrap page"><p className="state-msg">{t("amnahi.listingLoading")}</p></div>;
  }
  if (status === "error" || !listing) {
    return <div className="wrap page"><p className="state-msg error">{t("amnahi.listingError")}</p></div>;
  }

  const images = [listing.image, ...listing.gallery].filter(Boolean);
  const isOwnListing = user && Number(user.id) === Number(listing.seller_id);

  return (
    <div className="wrap page">
      {listing.status !== "publish" && (
        <p className="state-msg" style={{ background: "#fdf1f3", borderRadius: 10, padding: "10px 14px", marginBottom: 16, textAlign: "start" }}>
          {t("amnahi.pendingNotice")}
        </p>
      )}
      <div className="contact-grid">
        <div>
          <div className="item-thumb" style={{ width: "100%", height: 420, marginBottom: 10 }}>
            {images[activeImage] ? (
              <img src={images[activeImage]} alt={listing.name} style={{ width: "100%", height: "100%", objectFit: "cover" }} />
            ) : (
              <DressGlyph width={140} height={200} />
            )}
          </div>
          {images.length > 1 && (
            <div style={{ display: "flex", gap: 8 }}>
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
          <span className="story-eyebrow">{listing.dress_type || t("amnahi.eyebrow")}</span>
          <h1 style={{ fontSize: 26, color: "var(--ink-900)", margin: "6px 0 10px" }}>{listing.name}</h1>
          <p style={{ fontSize: 20, fontWeight: 700, color: "var(--rose-600)", margin: "0 0 14px" }}>
            {listing.price ? `${listing.price} ${t("amnahi.currency")}` : t("amnahi.priceOnContact")}
          </p>
          <p className="page-sub" style={{ textAlign: "start", marginBottom: 20 }}>{listing.description}</p>
          <p style={{ fontSize: 12.5, color: "var(--muted-500)", marginBottom: 20 }}>
            {t("amnahi.from")} <strong style={{ color: "var(--ink-700)" }}>{listing.seller_name}</strong>
          </p>

          {isOwnListing ? (
            <p className="state-msg" style={{ padding: 0, textAlign: "start" }}>{t("amnahi.ownListing")}</p>
          ) : (
            <>
              <button className="btn btn-rose" style={{ width: "100%" }} onClick={onInterested} disabled={interestBusy}>
                {interestBusy ? t("amnahi.interestedBusy") : t("amnahi.interested")}
              </button>
              {interestError && <p style={{ color: "var(--rose-600)", fontSize: 12.5, marginTop: 8 }}>{interestError}</p>}
            </>
          )}
        </div>
      </div>
    </div>
  );
}
