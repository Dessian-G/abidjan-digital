'use client';

import Image from 'next/image';
import Link from 'next/link';
import { useState } from 'react';
import { Menu, X } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import { Lang } from '@/lib/i18n';

function LangToggle({ lang, setLang, className = '' }: { lang: Lang; setLang: (l: Lang) => void; className?: string }) {
  return (
    <div className={`flex items-center rounded-lg border border-gray-200 p-0.5 text-xs font-bold ${className}`}>
      {(['fr', 'en'] as const).map(code => (
        <button
          key={code}
          type="button"
          onClick={() => setLang(code)}
          aria-pressed={lang === code}
          className="px-2 py-1 rounded-md transition-colors"
          style={lang === code ? { backgroundColor: '#0E2A63', color: 'white' } : { color: '#9ca3af' }}
        >
          {code.toUpperCase()}
        </button>
      ))}
    </div>
  );
}

export default function Header() {
  const [open, setOpen] = useState(false);
  const { lang, setLang, t } = useLanguage();

  const navLinks = [
    { href: '/', label: t.nav.home },
    { href: '/services', label: t.nav.services },
    { href: '/projets', label: t.nav.projects },
    { href: '/applications', label: t.nav.applications },
    { href: '/boutique', label: t.nav.boutique },
    { href: '/a-propos', label: t.nav.about },
    { href: '/contact', label: t.nav.contact },
  ];

  return (
    <header className="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
        <Link href="/" className="flex items-center gap-2">
          <Image
            src="/logo.jpg"
            alt="Logo Abidjan Digital.ia"
            width={44}
            height={44}
            className="rounded-lg object-contain"
          />
          <span className="font-bold text-lg hidden sm:block" style={{ color: '#0E2A63' }}>
            Abidjan Digital.ia
          </span>
        </Link>

        {/* Desktop nav */}
        <nav className="hidden md:flex items-center gap-6">
          {navLinks.map(({ href, label }) => (
            <Link
              key={href}
              href={href}
              className="text-sm font-medium text-gray-700 hover:text-[#F4791F] transition-colors"
            >
              {label}
            </Link>
          ))}
          <LangToggle lang={lang} setLang={setLang} className="ml-2" />
          <Link
            href="/contact"
            className="ml-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors"
            style={{ backgroundColor: '#F4791F' }}
            onMouseEnter={e => (e.currentTarget.style.backgroundColor = '#d6680f')}
            onMouseLeave={e => (e.currentTarget.style.backgroundColor = '#F4791F')}
          >
            {t.common.requestService}
          </Link>
        </nav>

        {/* Mobile burger */}
        <div className="md:hidden flex items-center gap-2">
          <LangToggle lang={lang} setLang={setLang} />
          <button
            className="p-2 rounded-lg text-gray-700"
            onClick={() => setOpen(!open)}
            aria-label="Menu"
          >
            {open ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>
      </div>

      {/* Mobile menu */}
      {open && (
        <div className="md:hidden bg-white border-t border-gray-100 px-4 pb-4">
          {navLinks.map(({ href, label }) => (
            <Link
              key={href}
              href={href}
              className="block py-3 text-sm font-medium text-gray-700 border-b border-gray-50 hover:text-[#F4791F]"
              onClick={() => setOpen(false)}
            >
              {label}
            </Link>
          ))}
          <Link
            href="/contact"
            className="mt-4 block text-center px-4 py-2 rounded-lg text-sm font-semibold text-white"
            style={{ backgroundColor: '#F4791F' }}
            onClick={() => setOpen(false)}
          >
            {t.common.requestService}
          </Link>
        </div>
      )}
    </header>
  );
}
