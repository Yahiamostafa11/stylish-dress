import LegalPage from "../components/LegalPage";

const SECTIONS = [
  { titleKey: "legal.cookie.s1.title", items: [{ type: "p", key: "legal.cookie.s1.p1" }] },
  {
    titleKey: "legal.cookie.s2.title",
    items: [
      { type: "heading", key: "legal.cookie.s2.h1" },
      { type: "p", key: "legal.cookie.s2.p1" },
      { type: "heading", key: "legal.cookie.s2.h2" },
      { type: "p", key: "legal.cookie.s2.p2" },
      { type: "heading", key: "legal.cookie.s2.h3" },
      { type: "p", key: "legal.cookie.s2.p3" },
      { type: "heading", key: "legal.cookie.s2.h4" },
      { type: "p", key: "legal.cookie.s2.p4" },
    ],
  },
  {
    titleKey: "legal.cookie.s3.title",
    items: [
      { type: "heading", key: "legal.cookie.s3.nTitle" },
      { type: "p", key: "legal.cookie.s3.nDesc" },
      {
        type: "list",
        keys: [
          "legal.cookie.s3.nItem1",
          "legal.cookie.s3.nItem2",
          "legal.cookie.s3.nItem3",
          "legal.cookie.s3.nItem4",
          "legal.cookie.s3.nItem5",
          "legal.cookie.s3.nItem6",
        ],
      },
      { type: "heading", key: "legal.cookie.s3.aTitle" },
      { type: "p", key: "legal.cookie.s3.aDesc" },
      { type: "list", keys: ["legal.cookie.s3.aItem1", "legal.cookie.s3.aItem2"] },
      { type: "heading", key: "legal.cookie.s3.mTitle" },
      { type: "p", key: "legal.cookie.s3.mDesc" },
      { type: "list", keys: ["legal.cookie.s3.mItem1", "legal.cookie.s3.mItem2"] },
      { type: "note", key: "legal.cookie.s3.note" },
    ],
  },
  {
    titleKey: "legal.cookie.s4.title",
    items: [
      { type: "p", key: "legal.cookie.s4.p1" },
      { type: "p", key: "legal.cookie.s4.p2" },
    ],
  },
  {
    titleKey: "legal.cookie.s5.title",
    items: [
      { type: "p", key: "legal.cookie.s5.p1" },
      { type: "p", key: "legal.cookie.s5.p2" },
    ],
  },
  {
    titleKey: "legal.cookie.s6.title",
    items: [
      { type: "p", key: "legal.cookie.s6.p1" },
      { type: "p", key: "legal.cookie.s6.p2" },
      {
        type: "contact",
        website: "https://policies.google.com/privacy",
        websiteLabelKey: "legal.cookie.s6.ref",
      },
    ],
  },
  { titleKey: "legal.cookie.s7.title", items: [{ type: "p", key: "legal.cookie.s7.p1" }] },
  {
    titleKey: "legal.cookie.s8.title",
    items: [
      { type: "p", key: "legal.cookie.s8.p1" },
      { type: "contact", email: "privacy@styliiiish.com" },
      { type: "p", key: "legal.cookie.s8.p3" },
    ],
  },
];

export default function CookiePolicy() {
  return (
    <LegalPage
      eyebrowKey="legal.cookie.eyebrow"
      titleKey="legal.cookie.title"
      subtitleKey="legal.cookie.subtitle"
      flagKeys={["legal.cookie.gdpr", "legal.cookie.consentMode", "legal.cookie.cookieyes"]}
      updatedLabelKey="legal.cookie.updatedLabel"
      updatedValueKey="legal.cookie.updatedValue"
      sections={SECTIONS}
    />
  );
}
