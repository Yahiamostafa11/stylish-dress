import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { trackPageview } from "../api/client";

const OPT_OUT_KEY = "styliiiish_notrack";

// Counts storefront visits for the owner dashboard (cookie-less, no personal data — see routes/track.php).
// Open any page with ?notrack=1 once to exclude that browser (handy for staff).
export default function PageTracker() {
  const { pathname } = useLocation();

  useEffect(() => {
    try {
      const params = new URLSearchParams(window.location.search);
      if (params.get("notrack") === "1") localStorage.setItem(OPT_OUT_KEY, "1");
      if (params.get("notrack") === "0") localStorage.removeItem(OPT_OUT_KEY);
      if (localStorage.getItem(OPT_OUT_KEY) === "1") return;
    } catch {
      // storage blocked — fall through and count the visit
    }
    if (navigator.doNotTrack === "1") return;

    trackPageview(pathname);
  }, [pathname]);

  return null;
}
