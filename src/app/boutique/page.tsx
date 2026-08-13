import type { Metadata } from 'next';
import { ShoppingBag, ExternalLink } from 'lucide-react';

export const metadata: Metadata = {
  title: 'Boutique — Abidjan Digital.ia',
  description:
    "La boutique en ligne d'Abidjan Digital.ia : formations, ressources et produits numériques.",
};

const BOUTIQUE_URL = 'https://fyxoqzyx.mychariow.shop/';

export default function BoutiquePage() {
  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">Notre boutique</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            Formations, ressources et produits numériques d&apos;Abidjan Digital.ia,
            disponibles directement en ligne.
          </p>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 sm:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-xl mx-auto text-center bg-white rounded-2xl border border-gray-100 shadow-sm p-8 sm:p-12">
          <div
            className="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-6"
            style={{ backgroundColor: 'rgba(14,42,99,0.06)' }}
          >
            <ShoppingBag size={36} style={{ color: '#0E2A63' }} />
          </div>
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            Découvrez nos offres
          </h2>
          <p className="text-gray-500 mb-8 text-sm sm:text-base">
            Notre boutique en ligne rassemble nos formations et ressources numériques,
            prêtes à l&apos;achat en quelques clics.
          </p>
          <a
            href={BOUTIQUE_URL}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105 text-sm sm:text-base"
            style={{ backgroundColor: '#F4791F' }}
          >
            Visiter la boutique <ExternalLink size={16} />
          </a>
        </div>
      </section>
    </>
  );
}
