import type { Metadata } from 'next';
import BoutiqueContent from './BoutiqueContent';

export const metadata: Metadata = {
  title: 'Boutique — Abidjan Digital.ia',
  description:
    "La boutique en ligne d'Abidjan Digital.ia : formations, ressources et produits numériques.",
};

export default function BoutiquePage() {
  return <BoutiqueContent />;
}
