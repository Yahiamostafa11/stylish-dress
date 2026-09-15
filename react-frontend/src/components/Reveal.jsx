import { useEffect, useRef, useState } from "react";

export default function Reveal({ children, delay = 0, className = "", as: Tag = "div", style = {} }) {
  const ref = useRef(null);
  const [inView, setInView] = useState(false);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setInView(true);
          observer.disconnect();
        }
      },
      { threshold: 0.15 }
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, []);

  return (
    <Tag
      ref={ref}
      className={`reveal${inView ? " reveal-in" : ""}${className ? " " + className : ""}`}
      style={{ ...style, transitionDelay: `${delay}s` }}
    >
      {children}
    </Tag>
  );
}
