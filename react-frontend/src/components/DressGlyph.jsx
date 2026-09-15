export default function DressGlyph({ width = 112, height = 190, opacity = 0.55, fill = "#fff", fillOpacity = 0.6, stroke = "#B79AA6" }) {
  return (
    <svg width={width} height={height} viewBox="0 0 112 190" fill="none" opacity={opacity}>
      <path
        d="M56 8c-7 0-12 5-12 11 0 5 3 8 3 12l-6 8c-4 5-7 12-9 20l-12 56c-2 9-6 30-6 30s18 10 42 10 42-10 42-10-4-21-6-30l-12-56c-2-8-5-15-9-20l-6-8c0-4 3-7 3-12 0-6-5-11-12-11z"
        fill={fill}
        fillOpacity={fillOpacity}
        stroke={stroke}
        strokeWidth="1.4"
      />
    </svg>
  );
}
