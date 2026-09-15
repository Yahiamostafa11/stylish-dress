import { useState } from "react";
import { useLanguage } from "../context/LanguageContext";

const CONTACT_KEYS = [
  {
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 2 .6 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.9.5 2.9.6a2 2 0 0 1 1.7 2z" /></svg>
    ),
    titleKey: "contact.callTitle",
    value: "01000000000",
  },
  {
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" /></svg>
    ),
    titleKey: "contact.whatsappTitle",
    value: "01000000000",
  },
  {
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
    ),
    titleKey: "contact.emailTitle",
    value: "hello@styliiiish.com",
  },
  {
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#BA5D70" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"><path d="M12 21s-7-4.6-7-11a7 7 0 0 1 14 0c0 6.4-7 11-7 11z" /><circle cx="12" cy="10" r="2.5" /></svg>
    ),
    titleKey: "contact.addressTitle",
    valueKey: "contact.addressValue",
  },
];

export default function Contact() {
  const { t } = useLanguage();
  const [form, setForm] = useState({ name: "", message: "" });
  const [sent, setSent] = useState(false);

  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const onSubmit = (e) => {
    e.preventDefault();
    setSent(true);
  };

  return (
    <div className="wrap page">
      <div className="page-head">
        <span className="story-eyebrow">{t("contact.eyebrow")}</span>
        <h1>{t("contact.title")}</h1>
        <p className="page-sub">{t("contact.subtitle")}</p>
      </div>

      <div className="contact-grid">
        <div className="contact-info">
          {CONTACT_KEYS.map((item) => (
            <div className="contact-item" key={item.titleKey}>
              <span className="ci-icon">{item.icon}</span>
              <div>
                <strong>{t(item.titleKey)}</strong>
                <span>{item.valueKey ? t(item.valueKey) : item.value}</span>
              </div>
            </div>
          ))}
        </div>

        <div className="contact-form">
          {sent ? (
            <p className="state-msg" style={{ padding: 0 }}>{t("contact.success")}</p>
          ) : (
            <form onSubmit={onSubmit}>
              <div className="field" style={{ marginBottom: 16 }}>
                <label><i>*</i>{t("contact.nameLabel")}</label>
                <input required value={form.name} onChange={(e) => setField("name", e.target.value)} />
              </div>
              <div className="field" style={{ marginBottom: 16 }}>
                <label><i>*</i>{t("contact.messageLabel")}</label>
                <textarea required value={form.message} onChange={(e) => setField("message", e.target.value)} />
              </div>
              <button className="btn btn-rose" style={{ width: "100%" }} type="submit">{t("contact.submit")}</button>
            </form>
          )}
        </div>
      </div>
    </div>
  );
}
