import type { Metadata } from 'next';
import AproposContent from './AproposContent';

export const metadata: Metadata = {
  title: 'À propos — Abidjan Digital.ia',
  description:
    "Découvrez l'histoire et la mission d'Abidjan Digital.ia, studio web & IA fondé par Max Guiro, présent à Fort Worth et Abidjan.",
};

export default function AProposPage() {
  return <AproposContent />;
}
