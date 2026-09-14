import LegalPage from "../components/LegalPage";

const SECTIONS = [
  {
    titleKey: "legal.privacy.s1.title",
    items: [
      { type: "p", key: "legal.privacy.s1.p1" },
      { type: "p", key: "legal.privacy.s1.p2" },
      { type: "p", key: "legal.privacy.s1.p3" },
    ],
  },
  {
    titleKey: "legal.privacy.s2.title",
    items: [
      { type: "p", key: "legal.privacy.s2.intro" },
      {
        type: "list",
        keys: [
          "legal.privacy.s2.item1",
          "legal.privacy.s2.item2",
          "legal.privacy.s2.item3",
          "legal.privacy.s2.item4",
          "legal.privacy.s2.item5",
          "legal.privacy.s2.item6",
          "legal.privacy.s2.item7",
        ],
      },
    ],
  },
  {
    titleKey: "legal.privacy.s3.title",
    items: [
      { type: "p", key: "legal.privacy.s3.intro" },
      {
        type: "list",
        keys: [
          "legal.privacy.s3.item1",
          "legal.privacy.s3.item2",
          "legal.privacy.s3.item3",
          "legal.privacy.s3.item4",
          "legal.privacy.s3.item5",
          "legal.privacy.s3.item6",
        ],
      },
    ],
  },
  {
    titleKey: "legal.privacy.s4.title",
    items: [
      { type: "p", key: "legal.privacy.s4.intro" },
      {
        type: "list",
        keys: [
          "legal.privacy.s4.item1",
          "legal.privacy.s4.item2",
          "legal.privacy.s4.item3",
          "legal.privacy.s4.item4",
        ],
      },
    ],
  },
  {
    titleKey: "legal.privacy.s5.title",
    items: [
      { type: "p", key: "legal.privacy.s5.p1" },
      { type: "p", key: "legal.privacy.s5.p2" },
    ],
  },
  {
    titleKey: "legal.privacy.s6.title",
    items: [
      { type: "p", key: "legal.privacy.s6.intro" },
      {
        type: "list",
        keys: [
          "legal.privacy.s6.item1",
          "legal.privacy.s6.item2",
          "legal.privacy.s6.item3",
          "legal.privacy.s6.item4",
          "legal.privacy.s6.item5",
          "legal.privacy.s6.item6",
        ],
      },
      { type: "p", key: "legal.privacy.s6.note" },
    ],
  },
  {
    titleKey: "legal.privacy.s7.title",
    items: [
      { type: "p", key: "legal.privacy.s7.p1" },
      { type: "p", key: "legal.privacy.s7.p2" },
      { type: "p", key: "legal.privacy.s7.p3" },
    ],
  },
  {
    titleKey: "legal.privacy.s8.title",
    items: [
      { type: "p", key: "legal.privacy.s8.p1" },
      { type: "p", key: "legal.privacy.s8.p2" },
      { type: "p", key: "legal.privacy.s8.p3" },
      { type: "p", key: "legal.privacy.s8.p4" },
    ],
  },
  {
    titleKey: "legal.privacy.s9.title",
    items: [
      {
        type: "list",
        keys: ["legal.privacy.s9.item1", "legal.privacy.s9.item2", "legal.privacy.s9.item3"],
      },
    ],
  },
  {
    titleKey: "legal.privacy.s10.title",
    items: [
      { type: "p", key: "legal.privacy.s10.intro" },
      {
        type: "list",
        keys: [
          "legal.privacy.s10.item1",
          "legal.privacy.s10.item2",
          "legal.privacy.s10.item3",
          "legal.privacy.s10.item4",
          "legal.privacy.s10.item5",
          "legal.privacy.s10.item6",
          "legal.privacy.s10.item7",
        ],
      },
      { type: "p", key: "legal.privacy.s10.outro" },
    ],
  },
  {
    titleKey: "legal.privacy.s11.title",
    items: [{ type: "p", key: "legal.privacy.s11.p1" }],
  },
  {
    titleKey: "legal.privacy.s12.title",
    items: [{ type: "p", key: "legal.privacy.s12.p1" }],
  },
  {
    titleKey: "legal.privacy.s13.title",
    items: [
      { type: "p", key: "legal.privacy.s13.p1" },
      { type: "note", key: "legal.privacy.questionsDesc", labelKey: "legal.privacy.questionsLabel" },
    ],
  },
];

export default function PrivacyPolicy() {
  return (
    <LegalPage
      eyebrowKey="legal.privacy.eyebrow"
      titleKey="legal.privacy.title"
      subtitleKey="legal.privacy.subtitle"
      updatedLabelKey="legal.privacy.updatedLabel"
      updatedValueKey="legal.privacy.updatedValue"
      sections={SECTIONS}
    />
  );
}
