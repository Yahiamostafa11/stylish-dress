import { createContext, useContext, useEffect, useState } from "react";
import { translations } from "../i18n/translations";

const LanguageContext = createContext(null);
const LANG_KEY = "styliiiish_lang";

// Arab League member states — IP-detected countries here default to Arabic.
const ARABIC_COUNTRY_CODES = new Set([
  "DZ", "BH", "KM", "DJ", "EG", "IQ", "JO", "KW", "LB", "LY",
  "MR", "MA", "OM", "PS", "QA", "SA", "SO", "SD", "SY", "TN", "AE", "YE",
]);

function readStoredLang() {
  try {
    return localStorage.getItem(LANG_KEY);
  } catch {
    return null;
  }
}

function detectFromBrowser() {
  const browserLang = navigator.language || navigator.userLanguage || "";
  return browserLang.toLowerCase().startsWith("ar") ? "ar" : "en";
}

export function LanguageProvider({ children }) {
  const [lang, setLangState] = useState(() => readStoredLang() || "ar");

  useEffect(() => {
    if (readStoredLang()) return; // manual/previous choice always wins

    let cancelled = false;
    fetch("https://ipapi.co/json/")
      .then((res) => res.json())
      .then((data) => {
        if (cancelled) return;
        const code = String(data?.country_code || "").toUpperCase();
        setLangState(ARABIC_COUNTRY_CODES.has(code) ? "ar" : "en");
      })
      .catch(() => {
        if (!cancelled) setLangState(detectFromBrowser());
      });

    return () => {
      cancelled = true;
    };
  }, []);

  useEffect(() => {
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";
  }, [lang]);

  const setLang = (next) => {
    setLangState(next);
    try {
      localStorage.setItem(LANG_KEY, next);
    } catch {
      // ignore write failures (private mode / storage disabled)
    }
  };

  const toggleLang = () => setLang(lang === "ar" ? "en" : "ar");

  const t = (key) => translations[lang]?.[key] ?? translations.ar[key] ?? key;

  return (
    <LanguageContext.Provider value={{ lang, setLang, toggleLang, t }}>
      {children}
    </LanguageContext.Provider>
  );
}

export function useLanguage() {
  const ctx = useContext(LanguageContext);
  if (!ctx) throw new Error("useLanguage must be used within LanguageProvider");
  return ctx;
}
