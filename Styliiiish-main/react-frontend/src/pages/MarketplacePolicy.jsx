import LegalPage from "../components/LegalPage";

const SECTIONS = [
  {
    titleKey: "legal.marketplace.s1.title",
    items: [
      { type: "p", key: "legal.marketplace.s1.p1" },
      { type: "p", key: "legal.marketplace.s1.p2" },
      {
        type: "list",
        keys: [
          "legal.marketplace.s1.item1",
          "legal.marketplace.s1.item2",
          "legal.marketplace.s1.item3",
          "legal.marketplace.s1.item4",
        ],
      },
      { type: "p", key: "legal.marketplace.s1.p3" },
    ],
  },
  {
    titleKey: "legal.marketplace.s2.title",
    items: [
      { type: "p", key: "legal.marketplace.s2.p1" },
      {
        type: "list",
        keys: ["legal.marketplace.s2.item1", "legal.marketplace.s2.item2", "legal.marketplace.s2.item3"],
      },
      { type: "p", key: "legal.marketplace.s2.p2" },
    ],
  },
  {
    titleKey: "legal.marketplace.s3.title",
    items: [
      { type: "p", key: "legal.marketplace.s3.p1" },
      {
        type: "list",
        keys: [
          "legal.marketplace.s3.item1",
          "legal.marketplace.s3.item2",
          "legal.marketplace.s3.item3",
          "legal.marketplace.s3.item4",
        ],
      },
      { type: "p", key: "legal.marketplace.s3.p2" },
    ],
  },
  {
    titleKey: "legal.marketplace.s4.title",
    items: [
      { type: "p", key: "legal.marketplace.s4.p1" },
      { type: "p", key: "legal.marketplace.s4.p2" },
      {
        type: "list",
        keys: [
          "legal.marketplace.s4.item1",
          "legal.marketplace.s4.item2",
          "legal.marketplace.s4.item3",
          "legal.marketplace.s4.item4",
        ],
      },
      { type: "p", key: "legal.marketplace.s4.p3" },
    ],
  },
  {
    titleKey: "legal.marketplace.s5.title",
    items: [
      { type: "p", key: "legal.marketplace.s5.p1" },
      {
        type: "list",
        keys: ["legal.marketplace.s5.item1", "legal.marketplace.s5.item2", "legal.marketplace.s5.item3"],
      },
      { type: "p", key: "legal.marketplace.s5.p2" },
    ],
  },
  {
    titleKey: "legal.marketplace.s6.title",
    items: [
      { type: "p", key: "legal.marketplace.s6.p1" },
      { type: "p", key: "legal.marketplace.s6.p2" },
      { type: "list", keys: ["legal.marketplace.s6.item1", "legal.marketplace.s6.item2"] },
      { type: "p", key: "legal.marketplace.s6.p3" },
    ],
  },
  {
    titleKey: "legal.marketplace.s7.title",
    items: [
      { type: "p", key: "legal.marketplace.s7.p1" },
      { type: "p", key: "legal.marketplace.s7.p2" },
      {
        type: "list",
        keys: ["legal.marketplace.s7.item1", "legal.marketplace.s7.item2", "legal.marketplace.s7.item3"],
      },
      { type: "p", key: "legal.marketplace.s7.p3" },
    ],
  },
  {
    titleKey: "legal.marketplace.s8.title",
    items: [
      { type: "p", key: "legal.marketplace.s8.p1" },
      {
        type: "list",
        keys: ["legal.marketplace.s8.item1", "legal.marketplace.s8.item2", "legal.marketplace.s8.item3"],
      },
      { type: "p", key: "legal.marketplace.s8.p2" },
    ],
  },
  {
    titleKey: "legal.marketplace.s9.title",
    items: [
      { type: "p", key: "legal.marketplace.s9.p1" },
      { type: "p", key: "legal.marketplace.s9.p2" },
    ],
  },
  { titleKey: "legal.marketplace.s10.title", items: [{ type: "p", key: "legal.marketplace.s10.p1" }] },
  {
    titleKey: "legal.marketplace.s11.title",
    items: [
      { type: "p", key: "legal.marketplace.s11.p1" },
      { type: "contact", email: "email@styliiiish.com", website: "https://styliiiish.com" },
    ],
  },
];

export default function MarketplacePolicy() {
  return (
    <LegalPage
      eyebrowKey="legal.marketplace.eyebrow"
      titleKey="legal.marketplace.title"
      subtitleKey="legal.marketplace.subtitle"
      sections={SECTIONS}
    />
  );
}
