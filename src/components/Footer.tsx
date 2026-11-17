'use client';

import Image from 'next/image';
import Link from 'next/link';
import { MapPin, Mail } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

export default function Footer() {
  const { t } = useLanguage();

  return (
    <footer className="text-white pt-10 sm:pt-12 pb-6" style={{ backgroundColor: '#0E2A63' }}>
      <div className="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
        {/* Brand */}
        <div className="col-span-2 sm:col-span-2 md:col-span-1">
          <div className="flex items-center gap-3 mb-4">
            <Image
              src="/logo.jpg"
              alt="Logo Abidjan Digital.ia"
              width={40}
              height={40}
              className="rounded-lg object-contain bg-white p-0.5"
            />
            <span className="font-bold text-lg">Abidjan Digital.ia</span>
          </div>
          <p className="text-sm text-blue-200 italic mb-4">
            {t.common.tagline}
          </p>
          <p className="text-xs text-blue-300">
            {t.footer.description}
          </p>
        </div>

        {/* Services */}
        <div>
          <h4 className="font-semibold mb-3 text-sm uppercase tracking-wide text-orange-400">
            {t.footer.servicesHeading}
          </h4>
          <ul className="space-y-2 text-sm text-blue-200">
            <li><Link href="/services#formations" className="hover:text-white transition-colors">{t.footer.services.formations}</Link></li>
            <li><Link href="/services#infographies" className="hover:text-white transition-colors">{t.footer.services.infographies}</Link></li>
            <li><Link href="/services#sites-web" className="hover:text-white transition-colors">{t.footer.services.sitesWeb}</Link></li>
          </ul>
        </div>

        {/* Navigation */}
        <div>
          <h4 className="font-semibold mb-3 text-sm uppercase tracking-wide text-orange-400">
            {t.footer.navHeading}
          </h4>
          <ul className="space-y-2 text-sm text-blue-200">
            <li><Link href="/" className="hover:text-white transition-colors">{t.nav.home}</Link></li>
            <li><Link href="/services" className="hover:text-white transition-colors">{t.nav.services}</Link></li>
            <li><Link href="/projets" className="hover:text-white transition-colors">{t.nav.projects}</Link></li>
            <li><Link href="/guides" className="hover:text-white transition-colors">{t.nav.guides}</Link></li>
            <li><Link href="/a-propos" className="hover:text-white transition-colors">{t.nav.about}</Link></li>
            <li><Link href="/contact" className="hover:text-white transition-colors">{t.nav.contact}</Link></li>
          </ul>
        </div>

        {/* Contact */}
        <div>
          <h4 className="font-semibold mb-3 text-sm uppercase tracking-wide text-orange-400">
            {t.footer.contactHeading}
          </h4>
          <ul className="space-y-3 text-sm text-blue-200">
            <li className="flex items-start gap-2">
              <Mail size={15} className="mt-0.5 shrink-0 text-green-400" />
              <a href="mailto:abidjandigital.ia@gmail.com" className="hover:text-white transition-colors break-all">
                abidjandigital.ia@gmail.com
              </a>
            </li>
            <li className="flex items-start gap-2">
              <MapPin size={15} className="mt-0.5 shrink-0 text-green-400" />
              <span>{t.footer.locationUS}</span>
            </li>
            <li className="flex items-start gap-2">
              <MapPin size={15} className="mt-0.5 shrink-0 text-green-400" />
              <span>{t.footer.locationCI}</span>
            </li>
          </ul>
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 mt-10 pt-6 border-t border-blue-800 text-center text-xs text-blue-400">
        © {new Date().getFullYear()} Abidjan Digital.ia — {t.footer.rights}
      </div>
    </footer>
  );
}
