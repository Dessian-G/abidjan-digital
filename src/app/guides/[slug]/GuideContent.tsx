'use client';

import Link from 'next/link';
import { useState } from 'react';
import { ArrowLeft, Check, Clock, Copy, Lightbulb, MessageCircle, Sparkles } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import GuideCard from '@/components/GuideCard';
import GuidesNewsletter from '@/components/GuidesNewsletter';
import { WHATSAPP_NUMBER } from '@/lib/apps.config';
import { GUIDES, GUIDE_CATEGORY_COLORS, formatGuideDate, getGuide, readingMinutes } from '@/lib/guides';

function PromptBox({ text, label, copy, copied }: { text: string; label: string; copy: string; copied: string }) {
  const [done, setDone] = useState(false);

  const onCopy = async () => {
    try {
      await navigator.clipboard.writeText(text);
    } catch {
      // Repli pour les navigateurs sans API presse-papiers
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    }
    setDone(true);
    window.setTimeout(() => setDone(false), 2000);
  };

  return (
    <div className="mt-4 rounded-xl overflow-hidden border" style={{ borderColor: 'rgba(14,42,99,0.15)' }}>
      <div className="flex items-center justify-between px-4 py-2" style={{ backgroundColor: '#0E2A63' }}>
        <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-white">
          <Sparkles size={14} style={{ color: '#FDBA74' }} /> {label}
        </span>
        <button
          type="button"
          onClick={onCopy}
          className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold text-white bg-white/10 hover:bg-white/20 transition-colors"
          aria-live="polite"
        >
          {done ? <Check size={14} /> : <Copy size={14} />} {done ? copied : copy}
        </button>
      </div>
      <p className="px-4 py-4 text-sm sm:text-[15px] leading-relaxed text-gray-800 whitespace-pre-wrap" style={{ backgroundColor: '#f7f9ff' }}>
        {text}
      </p>
    </div>
  );
}

export default function GuideContent({ slug }: { slug: string }) {
  const { lang, t } = useLanguage();
  const guide = getGuide(slug)!;
  const color = GUIDE_CATEGORY_COLORS[guide.category];

  const others = [
    ...GUIDES.filter(g => g.slug !== slug && g.category === guide.category),
    ...GUIDES.filter(g => g.slug !== slug && g.category !== guide.category),
  ].slice(0, 3);

  const whatsappHref = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(
    t.guides.whatsappQuestionPrefix + guide.title[lang] + t.guides.whatsappQuestionSuffix
  )}`;

  return (
    <>
      {/* HERO */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-white" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <Link href="/guides" className="inline-flex items-center gap-1.5 text-sm text-blue-200 hover:text-white mb-6">
            <ArrowLeft size={16} /> {t.guides.backToGuides}
          </Link>
          <div className="mb-4">
            <span className="px-3 py-1 rounded-full text-xs font-semibold text-white" style={{ backgroundColor: color === '#0E2A63' ? '#F4791F' : color }}>
              {t.guides.categories[guide.category]}
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold leading-tight mb-4">{guide.title[lang]}</h1>
          <p className="text-blue-200 text-base sm:text-lg mb-5">{guide.description[lang]}</p>
          <p className="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs sm:text-sm text-blue-300">
            <span className="inline-flex items-center gap-1.5">
              <Clock size={14} /> {readingMinutes(guide, lang)} {t.guides.minRead}
            </span>
            <span>
              {t.guides.updated} {formatGuideDate(guide.updated, lang)}
            </span>
            {guide.tools.length > 0 && <span>{guide.tools.join(' · ')}</span>}
          </p>
        </div>
      </section>

      {/* CONTENU */}
      <article className="py-10 sm:py-14 px-4 sm:px-6 bg-white">
        <div className="max-w-3xl mx-auto">
          <p className="text-base sm:text-lg leading-relaxed text-gray-700 mb-10 pl-4 border-l-4" style={{ borderColor: '#F4791F' }}>
            {guide.intro[lang]}
          </p>

          <div className="space-y-10">
            {guide.sections.map((s, i) => {
              const ListTag = s.ordered ? 'ol' : 'ul';
              return (
                <section key={s.title.fr}>
                  <h2 className="flex items-start gap-3 text-lg sm:text-xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
                    <span
                      className="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold text-white shrink-0"
                      style={{ backgroundColor: color === '#0E2A63' ? '#F4791F' : color }}
                    >
                      {i + 1}
                    </span>
                    <span className="pt-0.5">{s.title[lang]}</span>
                  </h2>

                  {s.text && <p className="text-gray-600 leading-relaxed mb-3">{s.text[lang]}</p>}

                  {s.bullets && (
                    <ListTag className={`space-y-2 text-gray-600 leading-relaxed pl-5 ${s.ordered ? 'list-decimal' : 'list-disc'} marker:text-orange-500 marker:font-bold`}>
                      {s.bullets[lang].map(b => (
                        <li key={b} className="pl-1">{b}</li>
                      ))}
                    </ListTag>
                  )}

                  {s.prompt && (
                    <PromptBox text={s.prompt[lang]} label={t.guides.promptLabel} copy={t.guides.copy} copied={t.guides.copied} />
                  )}
                  {s.prompts?.map(p => (
                    <PromptBox key={p.fr} text={p[lang]} label={t.guides.promptLabel} copy={t.guides.copy} copied={t.guides.copied} />
                  ))}

                  {s.tip && (
                    <div className="mt-4 flex gap-3 rounded-xl px-4 py-3 text-sm leading-relaxed" style={{ backgroundColor: 'rgba(79,181,43,0.08)' }}>
                      <Lightbulb size={18} className="shrink-0 mt-0.5" style={{ color: '#4FB52B' }} />
                      <p className="text-gray-700">
                        <strong style={{ color: '#2f7a17' }}>{t.guides.tipLabel} </strong>
                        {s.tip[lang]}
                      </p>
                    </div>
                  )}
                </section>
              );
            })}
          </div>

          {/* CTA */}
          <div className="mt-14 rounded-2xl p-6 sm:p-8 text-center" style={{ backgroundColor: '#f7f9ff' }}>
            <h2 className="text-xl sm:text-2xl font-extrabold mb-2" style={{ color: '#0E2A63' }}>{t.guides.ctaTitle}</h2>
            <p className="text-gray-500 mb-6 text-sm sm:text-base max-w-lg mx-auto">{t.guides.ctaText}</p>
            <div className="flex flex-col sm:flex-row gap-3 justify-center">
              <Link
                href="/contact"
                className="px-6 py-3 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105 text-sm sm:text-base"
                style={{ backgroundColor: '#F4791F' }}
              >
                {t.guides.ctaButton}
              </Link>
              <a
                href={whatsappHref}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold text-white text-sm sm:text-base transition-transform hover:scale-105"
                style={{ backgroundColor: '#4FB52B' }}
              >
                <MessageCircle size={18} /> {t.guides.ctaWhatsapp}
              </a>
            </div>
          </div>
        </div>
      </article>

      {/* AUTRES GUIDES */}
      <section className="pb-12 sm:pb-16 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <h2 className="text-lg sm:text-xl font-extrabold mb-5" style={{ color: '#0E2A63' }}>{t.guides.moreGuides}</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {others.map(g => (
              <GuideCard key={g.slug} guide={g} />
            ))}
          </div>
        </div>
      </section>

      <GuidesNewsletter />
    </>
  );
}
