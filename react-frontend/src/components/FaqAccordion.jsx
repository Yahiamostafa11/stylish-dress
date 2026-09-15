import { useState } from "react";
import { useLanguage } from "../context/LanguageContext";

/**
 * Minimal single-open accordion for question/answer pairs.
 * `items` is an array of { qKey, aKey }.
 *
 * Uncontrolled by default (own open/close state, `defaultOpen` picks the
 * initially-open index). Pass `openId` + `onToggle` to control it from a
 * parent instead — used by the Faq page so only one item stays open across
 * several grouped accordions at once, mirroring the old single-page Blade
 * FAQ script.
 */
export default function FaqAccordion({ items, defaultOpen = -1, openId, onToggle }) {
  const { t } = useLanguage();
  const [internalOpen, setInternalOpen] = useState(
    defaultOpen >= 0 ? items[defaultOpen]?.qKey : null
  );

  const isControlled = openId !== undefined && onToggle !== undefined;
  const currentOpen = isControlled ? openId : internalOpen;
  const toggle = (qKey) => {
    const next = currentOpen === qKey ? null : qKey;
    if (isControlled) onToggle(next);
    else setInternalOpen(next);
  };

  return (
    <div className="faq-list">
      {items.map((item) => {
        const isOpen = currentOpen === item.qKey;
        return (
          <article className={`faq-item${isOpen ? " open" : ""}`} key={item.qKey}>
            <button
              type="button"
              className="faq-btn"
              aria-expanded={isOpen}
              onClick={() => toggle(item.qKey)}
            >
              <span>{t(item.qKey)}</span>
              <span className="faq-icon">+</span>
            </button>
            {isOpen && (
              <div className="faq-content">
                <p>{t(item.aKey)}</p>
              </div>
            )}
          </article>
        );
      })}
    </div>
  );
}
