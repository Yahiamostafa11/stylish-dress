import { useState } from "react";
import Reveal from "../components/Reveal";
import FaqAccordion from "../components/FaqAccordion";
import { useLanguage } from "../context/LanguageContext";

const GROUPS = [
  {
    titleKey: "faq.sectionShipping",
    items: [
      { qKey: "faq.q1", aKey: "faq.a1" },
      { qKey: "faq.q2", aKey: "faq.a2" },
      { qKey: "faq.q3", aKey: "faq.a3" },
    ],
  },
  {
    titleKey: "faq.sectionReturns",
    items: [
      { qKey: "faq.q4", aKey: "faq.a4" },
      { qKey: "faq.q5", aKey: "faq.a5" },
    ],
  },
  {
    titleKey: "faq.sectionMarketplace",
    items: [
      { qKey: "faq.q6", aKey: "faq.a6" },
      { qKey: "faq.q7", aKey: "faq.a7" },
      { qKey: "faq.q8", aKey: "faq.a8" },
    ],
  },
  {
    titleKey: "faq.sectionOrders",
    items: [
      { qKey: "faq.q9", aKey: "faq.a9" },
      { qKey: "faq.q10", aKey: "faq.a10" },
    ],
  },
  {
    titleKey: "faq.sectionRental",
    items: [{ qKey: "faq.q11", aKey: "faq.a11" }],
  },
  {
    titleKey: "faq.sectionSupport",
    items: [{ qKey: "faq.q12", aKey: "faq.a12" }],
  },
];

export default function Faq() {
  const { t } = useLanguage();
  // Single shared "open" id across every group, so opening one question
  // closes any other that's open elsewhere on the page (matches the old
  // Blade FAQ page's one-at-a-time accordion). Starts on the first question.
  const [openId, setOpenId] = useState("faq.q1");

  return (
    <div className="wrap page legal-page">
      <div className="page-head">
        <span className="story-eyebrow">{t("faq.eyebrow")}</span>
        <h1>{t("faq.title")}</h1>
        <p className="page-sub">{t("faq.subtitle")}</p>
      </div>

      <Reveal as="div" className="faq-groups">
        {GROUPS.map((group) => (
          <div className="faq-group" key={group.titleKey}>
            <h2 className="faq-group-title">{t(group.titleKey)}</h2>
            <FaqAccordion items={group.items} openId={openId} onToggle={setOpenId} />
          </div>
        ))}
      </Reveal>
    </div>
  );
}
