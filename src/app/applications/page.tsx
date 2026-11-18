'use client';

import { useEffect, useState } from 'react';
import { AlertTriangle, ExternalLink, X } from 'lucide-react';
import {
  APPS,
  CATEGORIES,
  WHATSAPP_NUMBER,
  type AppCategory,
  type PortfolioApp,
} from '@/lib/apps.config';
import { useLanguage } from '@/context/LanguageContext';
import { Translations } from '@/lib/i18n';

const STATUT_COLORS: Record<PortfolioApp['statut'], string> = {
  'en-ligne': '#4FB52B',
  beta: '#F4791F',
  bientot: '#9CA3AF',
};

function lienWhatsApp(app: PortfolioApp, t: Translations): string {
  const numero = app.whatsapp ?? WHATSAPP_NUMBER;
  const message =
    app.statut === 'bientot'
      ? `${t.applications.whatsappNotifyMessagePrefix}${app.nom}.`
      : `${t.applications.whatsappInterestMessagePrefix}${app.nom}${t.applications.whatsappInterestMessageSuffix}`;
  return `https://wa.me/${numero}?text=${encodeURIComponent(message)}`;
}

function WhatsAppIcon({ size = 16 }: { size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="currentColor" aria-hidden>
      <path d="M12.04 2a9.9 9.9 0 0 0-8.5 14.97L2 22l5.18-1.5A9.9 9.9 0 1 0 12.04 2Zm0 18a8.05 8.05 0 0 1-4.1-1.12l-.3-.18-3.07.89.9-2.99-.2-.31A8.06 8.06 0 1 1 12.04 20Zm4.42-6.03c-.24-.12-1.43-.7-1.65-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06a6.57 6.57 0 0 1-1.94-1.2 7.3 7.3 0 0 1-1.34-1.67c-.14-.24-.02-.37.1-.49.11-.11.25-.28.37-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.47-.4-.4-.54-.41h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.12 3.64.58.25 1.03.4 1.38.51.58.18 1.1.16 1.52.1.46-.07 1.43-.59 1.63-1.15.2-.57.2-1.05.14-1.15-.06-.1-.22-.16-.46-.28Z" />
    </svg>
  );
}

function ConfirmOpenModal({ app, t, onCancel, onConfirm }: { app: PortfolioApp; t: Translations; onCancel: () => void; onConfirm: () => void }) {
  useEffect(() => {
    const onKeyDown = (e: KeyboardEvent) => e.key === 'Escape' && onCancel();
    window.addEventListener('keydown', onKeyDown);
    return () => window.removeEventListener('keydown', onKeyDown);
  }, [onCancel]);

  return (
    <div
      className="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 px-4"
      onClick={onCancel}
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirm-open-title"
        className="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl"
        onClick={e => e.stopPropagation()}
      >
        <div className="flex items-start justify-between gap-3 mb-3">
          <div className="flex items-center gap-2">
            <AlertTriangle size={20} style={{ color: '#F4791F' }} />
            <h3 id="confirm-open-title" className="font-bold text-base" style={{ color: '#0E2A63' }}>
              {t.applications.modal.title}
            </h3>
          </div>
          <button onClick={onCancel} aria-label={t.applications.modal.closeLabel} className="text-gray-400 hover:text-gray-600 shrink-0">
            <X size={18} />
          </button>
        </div>
        <p className="text-sm text-gray-600 leading-relaxed mb-6">
          <strong>{app.nom}</strong>{t.applications.modal.warningSuffix}
        </p>
        <div className="flex gap-3">
          <button
            onClick={onCancel}
            className="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50"
          >
            {t.applications.modal.cancel}
          </button>
          <button
            onClick={onConfirm}
            className="flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105"
            style={{ backgroundColor: app.couleur }}
          >
            {t.applications.modal.confirm}
          </button>
        </div>
      </div>
    </div>
  );
}

function AppCard({ app, t, onRequestOpen }: { app: PortfolioApp; t: Translations; onRequestOpen: (app: PortfolioApp) => void }) {
  const cliquable = app.statut !== 'bientot' && app.accesDirect !== false;
  const translated = t.applications.apps[app.id];

  return (
    <div className="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 overflow-hidden flex flex-col">
      <span className="block h-1.5 w-full" style={{ backgroundColor: app.couleur }} />

      <div className="p-5 sm:p-6 flex flex-col flex-1">
        <div className="flex items-center gap-3 mb-3">
          <span
            className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white"
            style={{ backgroundColor: app.couleur }}
          >
            {app.initiales}
          </span>
          <div className="min-w-0">
            <h3 className="font-bold text-base sm:text-lg truncate" style={{ color: '#0E2A63' }}>
              {app.nom}
            </h3>
            <span
              className="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold text-white"
              style={{ backgroundColor: STATUT_COLORS[app.statut] }}
            >
              {t.applications.statusLabels[app.statut]}
            </span>
          </div>
        </div>

        <p className="font-semibold text-sm mb-1.5" style={{ color: '#0E2A63' }}>
          {translated.tagline}
        </p>
        <p className="text-gray-500 text-sm leading-relaxed mb-4 flex-1">
          {translated.description}
        </p>

        <span className="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-4">
          {t.applications.categories[app.categorie]}
        </span>

        <div className="flex flex-wrap items-center gap-2">
          {cliquable && (
            <button
              type="button"
              onClick={() => onRequestOpen(app)}
              className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105"
              style={{ backgroundColor: app.couleur }}
            >
              {t.applications.openApp} <ExternalLink size={14} />
            </button>
          )}
          <a
            href={lienWhatsApp(app, t)}
            target="_blank"
            rel="noopener noreferrer"
            className={`inline-flex items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105 ${
              cliquable ? '' : 'flex-1'
            }`}
            style={{ backgroundColor: '#25D366' }}
            aria-label={`${t.applications.whatsappAriaPrefix}${app.nom}`}
          >
            <WhatsAppIcon />
            {app.statut === 'bientot' ? t.applications.notifyMe : t.applications.whatsappLabel}
          </a>
        </div>
      </div>
    </div>
  );
}

export default function ApplicationsPage() {
  const { t } = useLanguage();
  const [filtre, setFiltre] = useState<AppCategory | 'Toutes'>('Toutes');
  const [pendingApp, setPendingApp] = useState<PortfolioApp | null>(null);

  const confirmerOuverture = () => {
    if (pendingApp) window.open(pendingApp.url, '_blank', 'noopener,noreferrer');
    setPendingApp(null);
  };

  const appsVisibles =
    filtre === 'Toutes' ? APPS : APPS.filter(a => a.categorie === filtre);

  const categoriesActives = CATEGORIES.filter(c =>
    APPS.some(a => a.categorie === c)
  );

  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">
            {t.applications.heroTitle}
          </h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            {t.applications.heroSubtitle}
          </p>
        </div>
        <div className="max-w-4xl mx-auto mt-8 sm:mt-10">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src="/realisations/sites-et-applications.jpg"
            alt={t.applications.heroTitle}
            width={1400}
            height={934}
            className="w-full h-auto rounded-2xl shadow-2xl border border-white/10"
          />
        </div>
      </section>

      {/* GRID */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <div
            className="flex gap-2 overflow-x-auto pb-2 mb-8 sm:mb-10 justify-start sm:justify-center"
            role="tablist"
            aria-label={t.applications.filterAriaLabel}
          >
            {(['Toutes', ...categoriesActives] as const).map(c => (
              <button
                key={c}
                type="button"
                role="tab"
                aria-selected={filtre === c}
                onClick={() => setFiltre(c)}
                className="shrink-0 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-colors border"
                style={
                  filtre === c
                    ? { backgroundColor: '#0E2A63', color: '#fff', borderColor: '#0E2A63' }
                    : { color: '#0E2A63', borderColor: '#e5e7eb', backgroundColor: '#fff' }
                }
              >
                {c === 'Toutes' ? t.applications.categoryAll : t.applications.categories[c]}
              </button>
            ))}
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {appsVisibles.map(app => (
              <AppCard key={app.id} app={app} t={t} onRequestOpen={setPendingApp} />
            ))}
          </div>

          {appsVisibles.length === 0 && (
            <p className="py-16 text-center text-gray-500">
              {t.applications.emptyCategory}
            </p>
          )}
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            {t.applications.ctaTitle}
          </h2>
          <p className="text-gray-500 mb-6 text-sm sm:text-base">
            {t.applications.ctaText}
          </p>
          <a
            href={lienWhatsApp({ nom: t.applications.customProjectLabel, statut: 'beta' } as PortfolioApp, t)}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105 text-sm sm:text-base"
            style={{ backgroundColor: '#25D366' }}
          >
            <WhatsAppIcon /> {t.applications.ctaButton}
          </a>
        </div>
      </section>

      {pendingApp && (
        <ConfirmOpenModal
          app={pendingApp}
          t={t}
          onCancel={() => setPendingApp(null)}
          onConfirm={confirmerOuverture}
        />
      )}
    </>
  );
}
