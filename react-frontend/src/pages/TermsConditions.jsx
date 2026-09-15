import LegalPage from "../components/LegalPage";

const SECTIONS = [
  { titleKey: "legal.terms.s1.title", items: [{ type: "p", key: "legal.terms.s1.p1" }] },
  { titleKey: "legal.terms.s2.title", items: [{ type: "p", key: "legal.terms.s2.p1" }] },
  { titleKey: "legal.terms.s3.title", items: [{ type: "p", key: "legal.terms.s3.p1" }] },
  { titleKey: "legal.terms.s4.title", items: [{ type: "p", key: "legal.terms.s4.p1" }] },
  { titleKey: "legal.terms.s5.title", items: [{ type: "p", key: "legal.terms.s5.p1" }] },
  {
    titleKey: "legal.terms.s6.title",
    items: [
      { type: "p", key: "legal.terms.s6.p1" },
      { type: "p", key: "legal.terms.s6.p2" },
    ],
  },
  {
    titleKey: "legal.terms.s7.title",
    items: [
      { type: "p", key: "legal.terms.s7.p1" },
      { type: "p", key: "legal.terms.s7.p2" },
    ],
  },
  { titleKey: "legal.terms.s8.title", items: [{ type: "p", key: "legal.terms.s8.p1" }] },
  { titleKey: "legal.terms.s9.title", items: [{ type: "p", key: "legal.terms.s9.p1" }] },
  { titleKey: "legal.terms.s10.title", items: [{ type: "p", key: "legal.terms.s10.p1" }] },
  { titleKey: "legal.terms.s11.title", items: [{ type: "p", key: "legal.terms.s11.p1" }] },
  { titleKey: "legal.terms.s12.title", items: [{ type: "p", key: "legal.terms.s12.p1" }] },
  { titleKey: "legal.terms.s13.title", items: [{ type: "p", key: "legal.terms.s13.p1" }] },
  {
    titleKey: "legal.terms.s14.title",
    items: [
      { type: "p", key: "legal.terms.s14.p1" },
      { type: "contact", email: "email@styliiiish.com", website: "https://styliiiish.com" },
    ],
  },
];

export default function TermsConditions() {
  return (
    <LegalPage
      eyebrowKey="legal.terms.eyebrow"
      titleKey="legal.terms.title"
      subtitleKey="legal.terms.subtitle"
      sections={SECTIONS}
    />
  );
}
