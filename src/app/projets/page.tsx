import type { Metadata } from 'next';
import ProjetsContent from './ProjetsContent';

export const metadata: Metadata = {
  title: 'Projets — Abidjan Digital.ia',
  description:
    "Découvrez les projets réalisés par Abidjan Digital.ia : sites web, infographies et formations digitales & IA.",
};

export default function ProjetsPage() {
  return <ProjetsContent />;
}
