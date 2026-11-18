'use client';

import Link from 'next/link';
import { GraduationCap, Image as ImageIcon, Globe, CheckCircle } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const IDS = ['formations', 'infographies', 'sites-web'];
const ICONS = [GraduationCap, ImageIcon, Globe];
const COLORS = ['#4FB52B', '#F4791F', '#0E2A63'];
const BACKGROUNDS = ['rgba(79,181,43,0.08)', 'rgba(244,121,31,0.08)', 'rgba(14,42,99,0.08)'];
// Image de chaque service ('contain' = visuel affiché en entier, sans recadrage)
const IMAGES: { src: string; fit: 'cover' | 'contain'; position?: string }[] = [
  { src: '/realisations/formation-ia.jpg', fit: 'cover' },
  { src: '/realisations/bohobli-skin-care.jpg', fit: 'contain' },
  { src: '/realisations/sites-et-applications.jpg', fit: 'contain' },
];

export default function ServicesContent() {
  const { t } = useLanguage();

  const services = t.services.items.map((item, i) => ({
    ...item,
    id: IDS[i],
    icon: ICONS[i],
    color: COLORS[i],
    bg: BACKGROUNDS[i],
    image: IMAGES[i],
  }));

  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">{t.services.heroTitle}</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            {t.services.heroSubtitle}
          </p>
        </div>
      </section>

      {/* SERVICES */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto space-y-12 sm:space-y-16">
          {services.map(({ id, icon: Icon, color, bg, image, title, intro, benefits, deliverables }, idx) => (
            <div
              key={id}
              id={id}
              className={`flex flex-col ${idx % 2 !== 0 ? 'md:flex-row-reverse' : 'md:flex-row'} gap-6 sm:gap-8 items-start`}
            >
              {/* Visual card */}
              <div className="w-full md:w-2/5 rounded-2xl overflow-hidden shrink-0 shadow-sm" style={{ backgroundColor: bg }}>
                <div
                  className="w-full h-56 sm:h-64"
                  style={image.fit === 'contain' ? { backgroundColor: '#0E2A63' } : undefined}
                >
                  <img
                    src={image.src}
                    alt={title}
                    loading="lazy"
                    className={`w-full h-full ${image.fit === 'contain' ? 'object-contain' : 'object-cover'} ${image.position ?? ''}`}
                  />
                </div>
                <div className="flex items-center gap-3 px-5 py-4">
                  <div className="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style={{ backgroundColor: `${color}20` }}>
                    <Icon size={22} style={{ color }} />
                  </div>
                  <h2 className="text-lg sm:text-xl font-extrabold" style={{ color: '#0E2A63' }}>{title}</h2>
                </div>
              </div>

              {/* Content */}
              <div className="flex-1">
                <p className="text-gray-600 leading-relaxed mb-6">{intro}</p>

                <div className="mb-6">
                  <h3 className="font-bold mb-3" style={{ color: '#0E2A63' }}>{t.services.benefitsHeading}</h3>
                  <ul className="space-y-2">
                    {benefits.map(b => (
                      <li key={b} className="flex items-start gap-2 text-sm text-gray-600">
                        <CheckCircle size={16} className="mt-0.5 shrink-0" style={{ color: '#4FB52B' }} />
                        {b}
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="mb-6">
                  <h3 className="font-bold mb-3" style={{ color: '#0E2A63' }}>{t.services.deliverablesHeading}</h3>
                  <div className="flex flex-wrap gap-2">
                    {deliverables.map(d => (
                      <span key={d} className="px-3 py-1 rounded-full text-xs font-medium border" style={{ color, borderColor: color, backgroundColor: bg }}>
                        {d}
                      </span>
                    ))}
                  </div>
                </div>

                <Link
                  href={`/contact?service=${id}`}
                  className="inline-block px-6 py-2.5 rounded-xl font-semibold text-sm text-white transition-transform hover:scale-105"
                  style={{ backgroundColor: '#F4791F' }}
                >
                  {t.services.requestThis}
                </Link>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>{t.services.ctaTitle}</h2>
          <p className="text-gray-500 mb-6">{t.services.ctaText}</p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105"
            style={{ backgroundColor: '#F4791F' }}
          >
            {t.services.ctaButton}
          </Link>
        </div>
      </section>
    </>
  );
}
