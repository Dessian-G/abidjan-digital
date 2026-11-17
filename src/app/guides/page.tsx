import type { Metadata } from 'next';
import GuidesContent from './GuidesContent';

export const metadata: Metadata = {
  title: 'Guides gratuits — Abidjan Digital.ia',
  description:
    "Guides gratuits d'Abidjan Digital.ia : IA, prompts, WhatsApp Business, visuels Canva, Google Maps et site web. Des étapes claires et des prompts prêts à copier pour les entrepreneurs.",
};

export default function GuidesPage() {
  return <GuidesContent />;
}
