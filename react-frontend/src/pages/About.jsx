import BrandStory from "../components/BrandStory";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

export default function About() {
  const { t } = useLanguage();

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("about.eyebrow")}</span>
        <h1>{t("about.title")}</h1>
        <p className="page-sub">{t("about.subtitle")}</p>
      </div>

      <BrandStory />

      <Reveal as="section" className="page" style={{ marginTop: 40, maxWidth: 760, marginInline: "auto" }}>
        <h2 style={{ fontSize: 20, color: "var(--ink-900)", marginBottom: 10 }}>{t("about.visionTitle")}</h2>
        <p className="page-sub" style={{ marginBottom: 24 }}>{t("about.visionBody")}</p>

        <h2 style={{ fontSize: 20, color: "var(--ink-900)", marginBottom: 10 }}>{t("about.howTitle")}</h2>
        <p className="page-sub">{t("about.howBody")}</p>
      </Reveal>
    </div>
  );
}
