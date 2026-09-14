import LegalPage from "../components/LegalPage";
import FaqAccordion from "../components/FaqAccordion";
import Reveal from "../components/Reveal";
import { useLanguage } from "../context/LanguageContext";

const SECTIONS = [
  {
    titleKey: "legal.shipping.s1.title",
    items: [
      { type: "p", key: "legal.shipping.s1.p1" },
      { type: "p", key: "legal.shipping.s1.p2" },
    ],
  },
  {
    titleKey: "legal.shipping.s2.title",
    items: [
      { type: "heading", key: "legal.shipping.s2.h1" },
      { type: "p", key: "legal.shipping.s2.p1" },
      { type: "heading", key: "legal.shipping.s2.h2" },
      { type: "p", key: "legal.shipping.s2.p2" },
    ],
  },
  {
    titleKey: "legal.shipping.s3.title",
    items: [
      { type: "p", key: "legal.shipping.s3.p1" },
      { type: "p", key: "legal.shipping.s3.p2" },
    ],
  },
  {
    titleKey: "legal.shipping.s4.title",
    items: [
      { type: "p", key: "legal.shipping.s4.p1" },
      { type: "list", keys: ["legal.shipping.s4.item1", "legal.shipping.s4.item2"] },
      { type: "p", key: "legal.shipping.s4.p2" },
    ],
  },
  {
    titleKey: "legal.shipping.s5.title",
    items: [
      { type: "p", key: "legal.shipping.s5.p1" },
      { type: "p", key: "legal.shipping.s5.p2" },
    ],
  },
  {
    titleKey: "legal.shipping.s6.title",
    items: [
      { type: "p", key: "legal.shipping.s6.p1" },
      { type: "p", key: "legal.shipping.s6.p2" },
      { type: "p", key: "legal.shipping.s6.p3" },
    ],
  },
  { titleKey: "legal.shipping.s7.title", items: [{ type: "p", key: "legal.shipping.s7.p1" }] },
  {
    titleKey: "legal.shipping.s8.title",
    items: [
      { type: "p", key: "legal.shipping.s8.p1" },
      { type: "contact", email: "email@styliiiish.com", website: "https://styliiiish.com" },
    ],
  },
];

const FAQ_ITEMS = [
  { qKey: "legal.shipping.q1", aKey: "legal.shipping.a1" },
  { qKey: "legal.shipping.q2", aKey: "legal.shipping.a2" },
  { qKey: "legal.shipping.q3", aKey: "legal.shipping.a3" },
  { qKey: "legal.shipping.q4", aKey: "legal.shipping.a4" },
  { qKey: "legal.shipping.q5", aKey: "legal.shipping.a5" },
];

export default function ShippingPolicy() {
  const { t } = useLanguage();

  return (
    <>
      <LegalPage
        eyebrowKey="legal.shipping.eyebrow"
        titleKey="legal.shipping.title"
        subtitleKey="legal.shipping.subtitle"
        sections={SECTIONS}
      />
      <Reveal as="div" className="wrap legal-faq-wrap">
        <h2 className="faq-group-title">{t("legal.shipping.faqTitle")}</h2>
        <FaqAccordion items={FAQ_ITEMS} defaultOpen={0} />
      </Reveal>
    </>
  );
}
