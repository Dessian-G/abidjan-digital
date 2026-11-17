'use client';

import { useEffect, useMemo, useRef, useState } from 'react';
import { BookOpen, Search, X } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import GuideCard from '@/components/GuideCard';
import GuidesNewsletter from '@/components/GuidesNewsletter';
import { GUIDES, GUIDE_CATEGORIES, GUIDE_CATEGORY_COLORS, GuideCategory } from '@/lib/guides';

type Filter = 'all' | GuideCategory;

function normalize(s: string) {
  return s.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
}

export default function GuidesContent() {
  const { t } = useLanguage();
  const [query, setQuery] = useState('');
  const [filter, setFilter] = useState<Filter>('all');
  const searchRef = useRef<HTMLInputElement>(null);

  // Raccourci « / » pour aller dans la recherche
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key !== '/' || e.metaKey || e.ctrlKey || e.altKey) return;
      const el = e.target as HTMLElement | null;
      if (el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.isContentEditable)) return;
      e.preventDefault();
      searchRef.current?.focus();
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, []);

  const pinned = GUIDES.filter(g => g.pinned);

  const counts = useMemo(() => {
    const c = Object.fromEntries([["all", GUIDES.length], ...GUIDE_CATEGORIES.map(id => [id, 0])]) as Record<Filter, number>;
    GUIDES.forEach(g => (c[g.category] += 1));
    return c;
  }, []);

  const results = useMemo(() => {
    const q = normalize(query.trim());
    return GUIDES.filter(g => filter === 'all' || g.category === filter).filter(g => {
      if (!q) return true;
      const haystack = normalize(
        [
          g.title.fr,
          g.title.en,
          g.description.fr,
          g.description.en,
          t.guides.categories[g.category],
          ...g.tools,
        ].join(' ')
      );
      return q.split(/\s+/).every(word => haystack.includes(word));
    });
  }, [query, filter, t]);

  // Le guide « À la une » est mis en avant seulement sur la vue complète, sans recherche
  const showFeatured = filter === 'all' && !query.trim();
  const featured = showFeatured ? GUIDES.find(g => g.featured) : undefined;
  const list = featured ? results.filter(g => g.slug !== featured.slug) : results;

  const clear = () => {
    setQuery('');
    setFilter('all');
    searchRef.current?.focus();
  };

  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 text-white" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto text-center">
          <span className="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide mb-4" style={{ backgroundColor: 'rgba(244,121,31,0.18)', color: '#FDBA74' }}>
            {t.guides.eyebrow}
          </span>
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4 leading-tight">{t.guides.heroTitle}</h1>
          <p className="text-blue-200 text-base sm:text-lg mb-8">{t.guides.heroLead}</p>
          <div className="flex flex-col sm:flex-row gap-3 justify-center mb-8">
            <a
              href="#bibliotheque"
              className="px-6 py-3 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105 text-sm sm:text-base"
              style={{ backgroundColor: '#F4791F' }}
            >
              {t.guides.browseAll}
            </a>
            <a
              href="#newsletter"
              className="px-6 py-3 rounded-xl font-bold border border-white/30 hover:bg-white/10 transition-colors text-sm sm:text-base"
            >
              {t.guides.subscribe}
            </a>
          </div>
          <ul className="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-blue-200">
            <li>
              <strong className="text-white">{GUIDES.length}</strong> {t.guides.statGuides}
            </li>
            <li>{t.guides.statFree}</li>
            <li className="hidden md:block">
              {t.guides.statSearchPrefix}{' '}
              <kbd className="px-1.5 py-0.5 rounded border border-white/30 text-xs font-mono text-white">/</kbd>{' '}
              {t.guides.statSearch}
            </li>
          </ul>
        </div>
      </section>

      {/* COMMENCEZ ICI */}
      <section className="pt-12 sm:pt-16 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <h2 className="flex items-center gap-2 text-lg sm:text-xl font-extrabold mb-5" style={{ color: '#0E2A63' }}>
            <BookOpen size={20} style={{ color: '#F4791F' }} /> {t.guides.startHere}
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            {pinned.map(g => (
              <GuideCard key={g.slug} guide={g} badge={t.guides.pinned} large />
            ))}
          </div>
        </div>
      </section>

      {/* BIBLIOTHÈQUE */}
      <section id="bibliotheque" className="py-12 sm:py-16 px-4 sm:px-6 bg-white scroll-mt-20">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              {t.guides.libraryTitle}
            </h2>
            <p className="text-gray-500 max-w-xl mx-auto text-sm sm:text-base">{t.guides.librarySubtitle}</p>
          </div>

          {/* Recherche */}
          <div className="relative max-w-xl mx-auto mb-5">
            <label htmlFor="guides-search" className="sr-only">{t.guides.searchLabel}</label>
            <Search size={18} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" />
            <input
              id="guides-search"
              ref={searchRef}
              type="search"
              value={query}
              onChange={e => setQuery(e.target.value)}
              placeholder={t.guides.searchPlaceholder}
              className="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-orange-300"
            />
            {query ? (
              <button
                type="button"
                onClick={() => setQuery('')}
                aria-label={t.guides.clearSearch}
                className="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-md text-gray-400 hover:text-gray-600"
              >
                <X size={16} />
              </button>
            ) : (
              <kbd className="hidden md:block absolute right-4 top-1/2 -translate-y-1/2 px-1.5 py-0.5 rounded border border-gray-200 text-xs font-mono text-gray-400">/</kbd>
            )}
          </div>

          {/* Filtres */}
          <div className="flex flex-wrap gap-2 justify-center mb-6">
            {(['all', ...GUIDE_CATEGORIES] as Filter[]).map(id => {
              const active = filter === id;
              const color = id === 'all' ? '#0E2A63' : GUIDE_CATEGORY_COLORS[id];
              const label = id === 'all' ? t.guides.all : t.guides.categories[id];
              return (
                <button
                  key={id}
                  type="button"
                  onClick={() => setFilter(id)}
                  aria-pressed={active}
                  className="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold border transition-colors"
                  style={
                    active
                      ? { backgroundColor: color, color: '#fff', borderColor: color }
                      : { color: '#0E2A63', borderColor: '#e5e7eb', backgroundColor: '#fff' }
                  }
                >
                  {label} <span className={active ? 'opacity-80' : 'text-gray-400'}>{counts[id]}</span>
                </button>
              );
            })}
          </div>

          <p className="text-center text-sm text-gray-500 mb-8" aria-live="polite">
            <strong style={{ color: '#0E2A63' }}>{results.length}</strong>{' '}
            {results.length > 1 ? t.guides.showingMany : t.guides.showingOne}
          </p>

          {results.length === 0 ? (
            <div className="text-center py-12 px-4">
              <h3 className="text-lg font-bold mb-2" style={{ color: '#0E2A63' }}>{t.guides.emptyTitle}</h3>
              <p className="text-gray-500 text-sm mb-6 max-w-md mx-auto">{t.guides.emptyText}</p>
              <button
                type="button"
                onClick={clear}
                className="px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                style={{ backgroundColor: '#0E2A63' }}
              >
                {t.guides.clearSearch}
              </button>
            </div>
          ) : (
            <>
              {featured && (
                <div className="mb-4 sm:mb-6">
                  <GuideCard guide={featured} badge={t.guides.featured} large />
                </div>
              )}
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {list.map(g => (
                  <GuideCard key={g.slug} guide={g} />
                ))}
              </div>
            </>
          )}
        </div>
      </section>

      <GuidesNewsletter />
    </>
  );
}
