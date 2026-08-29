'use client';

import Link from 'next/link';
import { GraduationCap, Image as ImageIcon, Globe, CheckCircle, MapPin, Zap, Users, ExternalLink } from 'lucide-react';
import NetworkBackground from '@/components/NetworkBackground';
import TypewriterText from '@/components/TypewriterText';
import PulseLink from '@/components/PulseLink';
import { useLanguage } from '@/context/LanguageContext';

const SERVICE_ICONS = [GraduationCap, ImageIcon, Globe];
const SERVICE_COLORS = ['#4FB52B', '#F4791F', '#0E2A63'];
const SERVICE_HREFS = ['/services#formations', '/services#infographies', '/services#sites-web'];

const PROJECT_LINKS = [
  'https://carnet-djassa.vercel.app',
  'https://kaman-chantier.vercel.app',
  'https://ma-tontine-d2a32.web.app',
];

const REASON_ICONS = [Zap, MapPin, Users, CheckCircle];

export default function HomePage() {
  const { t } = useLanguage();

  const services = t.home.services.map((s, i) => ({
    ...s,
    icon: SERVICE_ICONS[i],
    color: SERVICE_COLORS[i],
    href: SERVICE_HREFS[i],
  }));

  const projectsPreview = t.home.projects.map((p, i) => ({ ...p, link: PROJECT_LINKS[i] }));

  const reasons = t.home.reasons.map((r, i) => ({ ...r, icon: REASON_ICONS[i] }));

  return (
    <>
      {/* HERO */}
      <section className="relative overflow-hidden py-14 sm:py-20 md:py-28 px-4 sm:px-6" style={{ backgroundColor: '#0E2A63' }}>
        <NetworkBackground />
        <div className="absolute inset-0 opacity-10"
          style={{
            backgroundImage: 'radial-gradient(circle at 20% 50%, #4FB52B 0%, transparent 50%), radial-gradient(circle at 80% 20%, #F4791F 0%, transparent 50%)',
          }}
        />
        <div className="relative max-w-4xl mx-auto text-center text-white">
          <div className="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-6 tracking-wide"
            style={{ backgroundColor: 'rgba(79,181,43,0.2)', color: '#7be84f', border: '1px solid rgba(79,181,43,0.4)' }}>
            {t.home.badge}
          </div>
          <h1 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight mb-4">
            {t.home.heroTitle}<br />
            <TypewriterText
              texts={t.home.typewriter}
              style={{ color: '#F4791F' }}
            />
          </h1>
          <p className="text-lg md:text-xl font-medium mb-3" style={{ color: '#c8d8ff' }}>
            {t.common.tagline}
          </p>
          <p className="text-sm sm:text-base md:text-lg text-blue-200 mb-8 sm:mb-10 max-w-2xl mx-auto px-2">
            {t.home.description}
          </p>
          <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4 sm:px-0">
            <PulseLink
              href="/contact"
              className="px-7 py-3 rounded-xl font-semibold text-white shadow-lg transition-transform hover:scale-105 text-sm sm:text-base text-center"
              style={{ backgroundColor: '#F4791F' }}
            >
              {t.common.requestService}
            </PulseLink>
            <Link
              href="/services"
              className="px-7 py-3 rounded-xl font-semibold border-2 border-white text-white transition-colors hover:bg-white hover:text-[#0E2A63] text-sm sm:text-base text-center"
            >
              {t.home.ctaServices}
            </Link>
          </div>
        </div>
      </section>

      {/* SERVICES */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8 sm:mb-12">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              {t.home.servicesHeading}
            </h2>
            <p className="text-gray-500 max-w-xl mx-auto">
              {t.home.servicesSubtitle}
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            {services.map(({ icon: Icon, title, description, color, href }) => (
              <Link
                key={title}
                href={href}
                className="group bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1"
              >
                <div className="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style={{ backgroundColor: `${color}15` }}>
                  <Icon size={24} style={{ color }} />
                </div>
                <h3 className="font-bold text-lg mb-2" style={{ color: '#0E2A63' }}>{title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{description}</p>
                <span className="inline-block mt-4 text-sm font-semibold transition-colors group-hover:underline" style={{ color: '#F4791F' }}>
                  {t.common.learnMore}
                </span>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* POURQUOI NOUS */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8 sm:mb-12">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              {t.home.reasonsHeading}
            </h2>
            <p className="text-gray-500 max-w-xl mx-auto">
              {t.home.reasonsSubtitle}
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            {reasons.map(({ icon: Icon, title, text }) => (
              <div key={title} className="flex gap-3 sm:gap-4 bg-white rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-sm">
                <div className="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style={{ backgroundColor: 'rgba(79,181,43,0.12)' }}>
                  <Icon size={20} style={{ color: '#4FB52B' }} />
                </div>
                <div>
                  <h3 className="font-bold mb-1" style={{ color: '#0E2A63' }}>{title}</h3>
                  <p className="text-gray-500 text-sm leading-relaxed">{text}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* PROJETS PREVIEW */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto text-center">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            {t.home.projectsHeading}
          </h2>
          <p className="text-gray-500 max-w-xl mx-auto mb-8 sm:mb-10">
            {t.home.projectsSubtitle}
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8 text-left">
            {projectsPreview.map(p => (
              <a
                key={p.title}
                href={p.link}
                target="_blank"
                rel="noopener noreferrer"
                className="group rounded-2xl border border-gray-100 p-5 sm:p-6 hover:shadow-md hover:-translate-y-1 transition-all bg-white"
              >
                <h3 className="font-bold mb-2 flex items-center gap-1.5" style={{ color: '#0E2A63' }}>
                  {p.title}
                  <ExternalLink size={14} className="opacity-0 group-hover:opacity-100 transition-opacity" style={{ color: '#F4791F' }} />
                </h3>
                <p className="text-gray-500 text-sm leading-relaxed">{p.description}</p>
              </a>
            ))}
          </div>
          <Link
            href="/projets"
            className="inline-block px-6 py-3 rounded-xl font-semibold text-sm sm:text-base transition-transform hover:scale-105 border-2"
            style={{ color: '#0E2A63', borderColor: '#0E2A63' }}
          >
            {t.home.viewAllProjects}
          </Link>
        </div>
      </section>

      {/* CTA FINAL */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#F4791F' }}>
        <div className="max-w-2xl mx-auto">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-4">
            {t.home.ctaFinalTitle}
          </h2>
          <p className="text-orange-100 mb-8 text-base">
            {t.home.ctaFinalText}
          </p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-[#F4791F] bg-white shadow-lg hover:shadow-xl transition-transform hover:scale-105 text-base"
          >
            {t.home.ctaFinalButton}
          </Link>
        </div>
      </section>
    </>
  );
}
