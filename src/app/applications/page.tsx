'use client';

import { useState } from 'react';
import { ExternalLink } from 'lucide-react';
import {
  APPS,
  CATEGORIES,
  STATUT_LABELS,
  WHATSAPP_NUMBER,
  type AppCategory,
  type PortfolioApp,
} from '@/lib/apps.config';

const STATUT_COLORS: Record<PortfolioApp['statut'], string> = {
  'en-ligne': '#4FB52B',
  beta: '#F4791F',
  bientot: '#9CA3AF',
};

function lienWhatsApp(app: PortfolioApp): string {
  const numero = app.whatsapp ?? WHATSAPP_NUMBER;
  const message =
    app.statut === 'bientot'
      ? `Bonjour ! Je souhaite être averti(e) du lancement de l'application ${app.nom}.`
      : `Bonjour ! Je suis intéressé(e) par l'application ${app.nom} et j'aimerais en savoir plus.`;
  return `https://wa.me/${numero}?text=${encodeURIComponent(message)}`;
}

function WhatsAppIcon({ size = 16 }: { size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="currentColor" aria-hidden>
      <path d="M12.04 2a9.9 9.9 0 0 0-8.5 14.97L2 22l5.18-1.5A9.9 9.9 0 1 0 12.04 2Zm0 18a8.05 8.05 0 0 1-4.1-1.12l-.3-.18-3.07.89.9-2.99-.2-.31A8.06 8.06 0 1 1 12.04 20Zm4.42-6.03c-.24-.12-1.43-.7-1.65-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06a6.57 6.57 0 0 1-1.94-1.2 7.3 7.3 0 0 1-1.34-1.67c-.14-.24-.02-.37.1-.49.11-.11.25-.28.37-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.47-.4-.4-.54-.41h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.7 2.6 4.12 3.64.58.25 1.03.4 1.38.51.58.18 1.1.16 1.52.1.46-.07 1.43-.59 1.63-1.15.2-.57.2-1.05.14-1.15-.06-.1-.22-.16-.46-.28Z" />
    </svg>
  );
}

function AppCard({ app }: { app: PortfolioApp }) {
  const cliquable = app.statut !== 'bientot';

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
              {STATUT_LABELS[app.statut]}
            </span>
          </div>
        </div>

        <p className="font-semibold text-sm mb-1.5" style={{ color: '#0E2A63' }}>
          {app.tagline}
        </p>
        <p className="text-gray-500 text-sm leading-relaxed mb-4 flex-1">
          {app.description}
        </p>

        <span className="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-4">
          {app.categorie}
        </span>

        <div className="flex flex-wrap items-center gap-2">
          {cliquable && (
            <a
              href={app.url}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105"
              style={{ backgroundColor: app.couleur }}
            >
              Ouvrir l&apos;app <ExternalLink size={14} />
            </a>
          )}
          <a
            href={lienWhatsApp(app)}
            target="_blank"
            rel="noopener noreferrer"
            className={`inline-flex items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105 ${
              cliquable ? '' : 'flex-1'
            }`}
            style={{ backgroundColor: '#25D366' }}
            aria-label={`Contacter sur WhatsApp au sujet de ${app.nom}`}
          >
            <WhatsAppIcon />
            {cliquable ? 'WhatsApp' : 'Être averti(e)'}
          </a>
        </div>
      </div>
    </div>
  );
}

export default function ApplicationsPage() {
  const [filtre, setFiltre] = useState<AppCategory | 'Toutes'>('Toutes');

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
            Nos applications
          </h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            Des outils numériques pensés pour les entrepreneurs africains — installables
            directement depuis votre navigateur, même sur une connexion instable. Une
            application vous intéresse ? Écrivez-nous en un clic sur WhatsApp.
          </p>
        </div>
      </section>

      {/* GRID */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <div
            className="flex gap-2 overflow-x-auto pb-2 mb-8 sm:mb-10 justify-start sm:justify-center"
            role="tablist"
            aria-label="Filtrer par catégorie"
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
                {c}
              </button>
            ))}
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            {appsVisibles.map(app => (
              <AppCard key={app.id} app={app} />
            ))}
          </div>

          {appsVisibles.length === 0 && (
            <p className="py-16 text-center text-gray-500">
              Aucune application dans cette catégorie pour le moment.
            </p>
          )}
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            Une idée d&apos;application pour votre activité ?
          </h2>
          <p className="text-gray-500 mb-6 text-sm sm:text-base">
            Parlez-nous de votre besoin — nous concevons des outils numériques sur mesure.
          </p>
          <a
            href={lienWhatsApp({ nom: 'un projet sur mesure', statut: 'beta' } as PortfolioApp)}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105 text-sm sm:text-base"
            style={{ backgroundColor: '#25D366' }}
          >
            <WhatsAppIcon /> Discuter sur WhatsApp
          </a>
        </div>
      </section>
    </>
  );
}
