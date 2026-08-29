import type { Metadata } from 'next';
import ServicesContent from './ServicesContent';

export const metadata: Metadata = {
  title: 'Services — Abidjan Digital.ia',
  description:
    "Découvrez nos services : formations digitales & IA, création d'infographies professionnelles et création de sites web sur mesure.",
};

export default function ServicesPage() {
  return <ServicesContent />;
}
