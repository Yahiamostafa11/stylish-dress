import { useState } from "react";
import { useLanguage } from "../context/LanguageContext";

export default function PasswordField({ value, onChange, required, minLength }) {
  const { t } = useLanguage();
  const [visible, setVisible] = useState(false);

  return (
    <div className="input-wrap">
      <input
        type={visible ? "text" : "password"}
        required={required}
        minLength={minLength}
        value={value}
        onChange={onChange}
        style={{ paddingInlineEnd: 40 }}
      />
      <button
        type="button"
        onClick={() => setVisible((v) => !v)}
        className="password-toggle"
        aria-label={visible ? t("password.hide") : t("password.show")}
      >
        {visible ? (
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#9C7F6C" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
            <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-10-8-10-8a18.6 18.6 0 0 1 4.22-5.44M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 10 8 10 8a18.7 18.7 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24" />
            <path d="M1 1l22 22" />
          </svg>
        ) : (
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#9C7F6C" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
            <path d="M1 12s3-8 11-8 11 8 11 8-3 8-11 8-11-8-11-8z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        )}
      </button>
    </div>
  );
}
