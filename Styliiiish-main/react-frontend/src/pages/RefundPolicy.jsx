import LegalPage from "../components/LegalPage";

const SECTIONS = [
  {
    titleKey: "legal.refund.s1.title",
    items: [
      { type: "p", key: "legal.refund.s1.p1" },
      {
        type: "list",
        keys: ["legal.refund.s1.item1", "legal.refund.s1.item2", "legal.refund.s1.item3"],
      },
      { type: "p", key: "legal.refund.s1.p2" },
      {
        type: "list",
        keys: ["legal.refund.s1.item4", "legal.refund.s1.item5", "legal.refund.s1.item6"],
      },
    ],
  },
  {
    titleKey: "legal.refund.s2.title",
    items: [
      { type: "p", key: "legal.refund.s2.p1" },
      {
        type: "list",
        keys: [
          "legal.refund.s2.item1",
          "legal.refund.s2.item2",
          "legal.refund.s2.item3",
          "legal.refund.s2.item4",
        ],
      },
      { type: "p", key: "legal.refund.s2.p2" },
    ],
  },
  {
    titleKey: "legal.refund.s3.title",
    items: [
      { type: "p", key: "legal.refund.s3.p1" },
      {
        type: "list",
        keys: ["legal.refund.s3.item1", "legal.refund.s3.item2", "legal.refund.s3.item3"],
      },
      { type: "p", key: "legal.refund.s3.p2" },
    ],
  },
  {
    titleKey: "legal.refund.s4.title",
    items: [
      { type: "p", key: "legal.refund.s4.p1" },
      {
        type: "list",
        keys: ["legal.refund.s4.item1", "legal.refund.s4.item2", "legal.refund.s4.item3"],
      },
      { type: "p", key: "legal.refund.s4.p2" },
    ],
  },
  {
    titleKey: "legal.refund.s5.title",
    items: [
      { type: "p", key: "legal.refund.s5.p1" },
      { type: "list", keys: ["legal.refund.s5.item1", "legal.refund.s5.item2"] },
      { type: "p", key: "legal.refund.s5.p2" },
    ],
  },
  {
    titleKey: "legal.refund.s6.title",
    items: [
      { type: "p", key: "legal.refund.s6.p1" },
      { type: "contact", email: "email@styliiiish.com", website: "https://styliiiish.com" },
    ],
  },
];

export default function RefundPolicy() {
  return (
    <LegalPage
      eyebrowKey="legal.refund.eyebrow"
      titleKey="legal.refund.title"
      subtitleKey="legal.refund.subtitle"
      sections={SECTIONS}
    />
  );
}
