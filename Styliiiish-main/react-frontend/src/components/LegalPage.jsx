import Reveal from "./Reveal";
import { useLanguage } from "../context/LanguageContext";

/**
 * Shared renderer for the legal/policy pages (privacy, terms, refund,
 * shipping, cookies, marketplace). Each page passes translation-key
 * driven `sections` so we avoid six near-identical page components.
 *
 * Section item shapes:
 *   { type: "p", key }                 -> <p>
 *   { type: "heading", key }           -> <h3> sub-heading inside a section
 *   { type: "list", keys: [...] }      -> <ul><li>
 *   { type: "note", key, labelKey? }    -> highlighted callout box, optional bold lead-in
 *   { type: "contact", email?, website?, websiteLabelKey? } -> mailto / website links
 */
export default function LegalPage({
  eyebrowKey,
  titleKey,
  subtitleKey,
  updatedLabelKey,
  updatedValueKey,
  flagKeys,
  sections,
}) {
  const { t } = useLanguage();

  return (
    <div className="wrap page legal-page">
      <div className="page-head">
        <span className="story-eyebrow">{t(eyebrowKey)}</span>
        <h1>{t(titleKey)}</h1>
        <p className="page-sub">{t(subtitleKey)}</p>
        {flagKeys && flagKeys.length > 0 && (
          <div className="legal-flags">
            {flagKeys.map((k) => (
              <span className="legal-flag" key={k}>{t(k)}</span>
            ))}
          </div>
        )}
        {updatedLabelKey && (
          <p className="legal-updated">
            <strong>{t(updatedLabelKey)}:</strong> {t(updatedValueKey)}
          </p>
        )}
      </div>

      <Reveal as="div" className="legal-sections">
        {sections.map((section) => (
          <article className="legal-section" key={section.titleKey}>
            <h2>{t(section.titleKey)}</h2>
            {section.items.map((item, idx) => (
              <LegalItem key={idx} item={item} t={t} />
            ))}
          </article>
        ))}
      </Reveal>
    </div>
  );
}

function LegalItem({ item, t }) {
  if (item.type === "heading") {
    return <h3 className="legal-sub">{t(item.key)}</h3>;
  }
  if (item.type === "list") {
    return (
      <ul className="legal-list">
        {item.keys.map((k) => (
          <li key={k}>{t(k)}</li>
        ))}
      </ul>
    );
  }
  if (item.type === "note") {
    return (
      <div className="legal-note">
        {item.labelKey && <strong>{t(item.labelKey)} </strong>}
        {t(item.key)}
      </div>
    );
  }
  if (item.type === "contact") {
    return (
      <p className="legal-contact">
        {item.email && <a href={`mailto:${item.email}`}>{item.email}</a>}
        {item.website && (
          <a href={item.website} target="_blank" rel="noopener noreferrer">
            {item.websiteLabelKey ? t(item.websiteLabelKey) : item.website.replace(/^https?:\/\//, "")}
          </a>
        )}
      </p>
    );
  }
  return <p>{t(item.key)}</p>;
}
