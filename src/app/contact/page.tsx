import type { Metadata } from 'next';
import ContactContent from './ContactContent';

export const metadata: Metadata = {
  title: 'Contact — Abidjan Digital.ia',
  description:
    'Contactez Abidjan Digital.ia pour une demande de service : formations IA, infographies, création de sites web.',
};

export default function ContactPage() {
  return <ContactContent />;
}
