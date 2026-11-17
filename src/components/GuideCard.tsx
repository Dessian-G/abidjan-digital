'use client';

import Link from 'next/link';
import { ArrowRight, Clock } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import { Guide, GUIDE_CATEGORY_COLORS, readingMinutes } from '@/lib/guides';

interface GuideCardProps {
  guide: Guide;
  badge?: string;
  large?: boolean;
}

export default function GuideCard({ guide, badge, large = false }: GuideCardProps) {
  const { lang, t } = useLanguage();
  const color = GUIDE_CATEGORY_COLORS[guide.category];
  const minutes = readingMinutes(guide, lang);

  return (
    <Link
      href={`/guides/${guide.slug}`}
      className={`group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 flex flex-col overflow-hidden ${
        large ? 'p-6 sm:p-8' : 'p-5 sm:p-6'
      }`}
      style={{ borderTop: `4px solid ${color}` }}
    >
      <div className="flex flex-wrap items-center gap-2 mb-3">
        <span
          className="px-2.5 py-0.5 rounded-full text-xs font-semibold"
          style={{ color, backgroundColor: `${color}12` }}
        >
          {t.guides.categories[guide.category]}
        </span>
        {badge && (
          <span
            className="px-2.5 py-0.5 rounded-full text-xs font-bold text-white"
            style={{ backgroundColor: '#F4791F' }}
          >
            {badge}
          </span>
        )}
      </div>

      <h3
        className={`font-bold mb-2 leading-snug ${large ? 'text-lg sm:text-xl' : 'text-base sm:text-lg'}`}
        style={{ color: '#0E2A63' }}
      >
        {guide.title[lang]}
      </h3>
      <p className="text-gray-500 text-sm leading-relaxed mb-3 flex-1">{guide.description[lang]}</p>
      {guide.tools.length > 0 && (
        <p className="text-xs text-gray-400 mb-4 truncate">{guide.tools.slice(0, 3).join(' · ')}</p>
      )}

      <div className="flex items-center justify-between gap-2 pt-3 border-t border-gray-50">
        <span className="inline-flex items-center gap-1.5 text-xs text-gray-400 whitespace-nowrap">
          <Clock size={13} />
          {minutes} {t.guides.minRead}
        </span>
        <span
          className="inline-flex items-center gap-1 text-sm font-semibold group-hover:underline"
          style={{ color: '#F4791F' }}
        >
          {t.guides.readGuide} <ArrowRight size={14} className="transition-transform group-hover:translate-x-0.5" />
        </span>
      </div>
    </Link>
  );
}
