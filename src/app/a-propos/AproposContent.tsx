'use client';

import Image from 'next/image';
import Link from 'next/link';
import { MapPin, Target, Heart, Lightbulb } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';

const VALUE_ICONS = [Target, Heart, Lightbulb];

export default function AproposContent() {
  const { t } = useLanguage();

  const values = t.apropos.values.map((v, i) => ({ ...v, icon: VALUE_ICONS[i] }));

  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">{t.apropos.heroTitle}</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            {t.apropos.heroSubtitle}
          </p>
        </div>
      </section>

      {/* MISSION & VALEURS */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8 sm:mb-12">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              {t.apropos.whoWeAreHeading}
            </h2>
            <p className="text-gray-500 max-w-2xl mx-auto leading-relaxed">
              {t.apropos.whoWeAreText}
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            {values.map(({ icon: Icon, title, text }) => (
              <div key={title} className="bg-gray-50 rounded-2xl p-5 sm:p-6 border border-gray-100">
                <div className="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style={{ backgroundColor: 'rgba(14,42,99,0.08)' }}>
                  <Icon size={22} style={{ color: '#0E2A63' }} />
                </div>
                <h3 className="font-bold mb-2" style={{ color: '#0E2A63' }}>{title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* FONDATEUR */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-4xl mx-auto">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-8 sm:mb-10 text-center" style={{ color: '#0E2A63' }}>
            {t.apropos.founderHeading}
          </h2>
          <div className="flex flex-col md:flex-row gap-6 sm:gap-10 items-center">
            <div className="shrink-0">
              <Image
                src="/max-guiro.jpg"
                alt="Max Guiro, fondateur d'Abidjan Digital.ia"
                width={240}
                height={300}
                className="rounded-2xl object-cover shadow-lg w-[180px] h-[225px] sm:w-[240px] sm:h-[300px]"
              />
            </div>
            <div>
              <h3 className="text-xl font-extrabold mb-1" style={{ color: '#0E2A63' }}>{t.apropos.founderName}</h3>
              <p className="text-sm font-semibold mb-4" style={{ color: '#F4791F' }}>{t.apropos.founderRole}</p>
              <div className="space-y-3 text-gray-600 leading-relaxed text-sm">
                {t.apropos.founderParagraphs.map(p => (
                  <p key={p}>{p}</p>
                ))}
                <p>
                  {t.apropos.founderMissionPrefix}
                  <strong className="italic" style={{ color: '#0E2A63' }}>{t.apropos.founderMissionWords}</strong>
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* DOUBLE IMPLANTATION */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-4xl mx-auto text-center">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-4" style={{ color: '#0E2A63' }}>
            {t.apropos.presenceHeading}
          </h2>
          <p className="text-gray-500 mb-10 max-w-xl mx-auto">
            {t.apropos.presenceSubtitle}
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div className="rounded-2xl p-5 sm:p-8 border-2 text-left" style={{ borderColor: '#4FB52B', backgroundColor: 'rgba(79,181,43,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <span className="text-2xl">🇺🇸</span>
                <div>
                  <p className="font-bold text-lg" style={{ color: '#0E2A63' }}>{t.apropos.usTitle}</p>
                  <p className="text-sm text-gray-400">{t.apropos.usCountry}</p>
                </div>
              </div>
              <div className="flex items-start gap-2 text-sm text-gray-600">
                <MapPin size={15} className="mt-0.5 shrink-0" style={{ color: '#4FB52B' }} />
                <p>{t.apropos.usText}</p>
              </div>
            </div>
            <div className="rounded-2xl p-5 sm:p-8 border-2 text-left" style={{ borderColor: '#F4791F', backgroundColor: 'rgba(244,121,31,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <span className="text-2xl">🇨🇮</span>
                <div>
                  <p className="font-bold text-lg" style={{ color: '#0E2A63' }}>{t.apropos.ciTitle}</p>
                  <p className="text-sm text-gray-400">{t.apropos.ciCountry}</p>
                </div>
              </div>
              <div className="flex items-start gap-2 text-sm text-gray-600">
                <MapPin size={15} className="mt-0.5 shrink-0" style={{ color: '#F4791F' }} />
                <p>{t.apropos.ciText}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center text-white" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3">{t.apropos.ctaTitle}</h2>
          <p className="text-blue-200 mb-6">{t.apropos.ctaText}</p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-[#0E2A63] bg-white shadow-md hover:shadow-lg transition-transform hover:scale-105"
          >
            {t.apropos.ctaButton}
          </Link>
        </div>
      </section>
    </>
  );
}
