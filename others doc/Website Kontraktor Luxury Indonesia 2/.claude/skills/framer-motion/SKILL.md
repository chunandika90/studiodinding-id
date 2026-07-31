---
name: framer-motion
description: Framer Motion animation patterns for React/Next.js sections — scroll reveals, staggered lists, scroll-linked expand/hero effects, page transitions. Use when a React/Next.js project needs section-by-section entrance animations or scroll-driven effects, and framer-motion is (or can be) a dependency.
---

# Framer Motion — Section Animation Patterns

Reference patterns for animating page sections with `framer-motion` in React/Next.js projects. Only applies to real React/Next.js codebases with a bundler (npm/vite/webpack) — `framer-motion` is an npm package and cannot run in a plain static HTML site without a build step.

Install: `npm install framer-motion`

## 1. Fade/slide reveal on scroll (per-section entrance)

```tsx
import { motion } from 'framer-motion';

<motion.section
  initial={{ opacity: 0, y: 28 }}
  whileInView={{ opacity: 1, y: 0 }}
  viewport={{ once: true, amount: 0.2 }}
  transition={{ duration: 0.7, ease: [0.2, 0.8, 0.2, 1] }}
>
  ...
</motion.section>
```
- `viewport={{ once: true }}` avoids re-triggering every time the user scrolls back up.
- Keep the y offset 20-30px — larger reads as a slide, not a reveal.

## 2. Staggered children (cards, list items, grid)

```tsx
const container = {
  hidden: {},
  show: { transition: { staggerChildren: 0.08 } },
};
const item = {
  hidden: { opacity: 0, y: 20 },
  show: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' } },
};

<motion.div variants={container} initial="hidden" whileInView="show" viewport={{ once: true }}>
  {items.map((it) => (
    <motion.div key={it.id} variants={item}>{it.content}</motion.div>
  ))}
</motion.div>
```

## 3. Scroll-linked hero/media expansion (like "Scroll Expand Media Hero")

Ties element scale/size directly to scroll progress instead of a one-shot trigger — use `useScroll` + `useTransform`.

```tsx
import { useRef } from 'react';
import { motion, useScroll, useTransform } from 'framer-motion';

function ScrollExpandHero() {
  const ref = useRef(null);
  const { scrollYProgress } = useScroll({ target: ref, offset: ['start start', 'end start'] });

  const width = useTransform(scrollYProgress, [0, 1], ['45vw', '100vw']);
  const height = useTransform(scrollYProgress, [0, 1], ['50vh', '100vh']);
  const radius = useTransform(scrollYProgress, [0, 1], [24, 0]);
  const textOpacity = useTransform(scrollYProgress, [0, 0.6], [1, 0]);

  return (
    <section ref={ref} style={{ height: '220vh', position: 'relative' }}>
      <div style={{ position: 'sticky', top: 0, height: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
        <motion.div style={{ width, height, borderRadius: radius, overflow: 'hidden', position: 'relative' }}>
          <img src="/hero.jpg" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
          <motion.h2 style={{ opacity: textOpacity, position: 'absolute', inset: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            Immersive Experience
          </motion.h2>
        </motion.div>
      </div>
    </section>
  );
}
```
- The wrapping section must be taller than 100vh (e.g. `220vh`) to create the scroll runway; the sticky inner div is what stays pinned while progress animates.
- `offset: ['start start', 'end start']` maps progress 0→1 across exactly that runway.

## 4. Page/route transitions (Next.js App Router)

```tsx
'use client';
import { AnimatePresence, motion } from 'framer-motion';
import { usePathname } from 'next/navigation';

<AnimatePresence mode="wait">
  <motion.div
    key={pathname}
    initial={{ opacity: 0 }}
    animate={{ opacity: 1 }}
    exit={{ opacity: 0 }}
    transition={{ duration: 0.3 }}
  >
    {children}
  </motion.div>
</AnimatePresence>
```

## Do
- Animate `opacity`/`transform` (`x`, `y`, `scale`) — stays on the compositor thread, no layout thrash.
- Use `viewport={{ once: true }}` for entrance animations unless the design explicitly wants replay on re-scroll.
- Keep durations 0.3–0.8s for section reveals; 0.15–0.3s for hover/tap micro-interactions.

## Don't
- Don't animate `width`/`height`/`top`/`left` directly for hover effects — triggers layout reflow (the scroll-expand pattern above is the deliberate exception, gated to a slow scroll-driven change, not a hover).
- Don't stagger more than ~12 items without capping `staggerChildren` low (<0.05s) — feels sluggish otherwise.
- Don't skip `prefers-reduced-motion` handling for larger motion (`useReducedMotion()` hook) on accessibility-sensitive projects.

## Related
See [[ui-ux-pro-max]] skill's `motion` domain for GSAP-based equivalents when the project already uses GSAP instead of Framer Motion.
