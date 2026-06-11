'use client';

import Image from 'next/image';
import Link from 'next/link';
import { useState } from 'react';
import { Menu, X } from 'lucide-react';

const navLinks = [
  { href: '/', label: 'Accueil' },
  { href: '/services', label: 'Services' },
  { href: '/a-propos', label: 'À propos' },
  { href: '/contact', label: 'Contact' },
];

export default function Header() {
  const [open, setOpen] = useState(false);

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
          <Link
            href="/contact"
            className="ml-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors"
            style={{ backgroundColor: '#F4791F' }}
            onMouseEnter={e => (e.currentTarget.style.backgroundColor = '#d6680f')}
            onMouseLeave={e => (e.currentTarget.style.backgroundColor = '#F4791F')}
          >
            Demander un service
          </Link>
        </nav>

        {/* Mobile burger */}
        <button
          className="md:hidden p-2 rounded-lg text-gray-700"
          onClick={() => setOpen(!open)}
          aria-label="Menu"
        >
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>
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
            Demander un service
          </Link>
        </div>
      )}
    </header>
  );
}
