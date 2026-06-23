import type { Metadata } from 'next';
import { Suspense } from 'react';
import { Mail, MapPin } from 'lucide-react';
import ContactForm from '@/components/ContactForm';

export const metadata: Metadata = {
  title: 'Contact — Abidjan Digital.ia',
  description:
    'Contactez Abidjan Digital.ia pour une demande de service : formations IA, infographies, création de sites web.',
};

export default function ContactPage() {
  return (
    <>
      {/* HERO */}
      <section className="py-16 px-4 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-3xl md:text-4xl font-extrabold mb-4">Demandez un service</h1>
          <p className="text-blue-200 text-lg">
            Décrivez votre besoin en quelques lignes — nous vous répondons sous 24h.
          </p>
        </div>
      </section>

      <section className="py-20 px-4 bg-white">
        <div className="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
          {/* Formulaire */}
          <div className="md:col-span-2">
            <h2 className="text-xl font-extrabold mb-6" style={{ color: '#0E2A63' }}>
              Formulaire de demande
            </h2>
            <Suspense fallback={<div className="h-64 animate-pulse bg-gray-100 rounded-2xl" />}>
              <ContactForm />
            </Suspense>
          </div>

          {/* Coordonnées */}
          <div>
            <h2 className="text-xl font-extrabold mb-6" style={{ color: '#0E2A63' }}>
              Nos coordonnées
            </h2>
            <div className="space-y-6">
              <div className="flex items-start gap-3">
                <div className="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'rgba(244,121,31,0.12)' }}>
                  <Mail size={18} style={{ color: '#F4791F' }} />
                </div>
                <div>
                  <p className="font-semibold text-sm mb-1" style={{ color: '#0E2A63' }}>Email</p>
                  <a
                    href="mailto:abidjandigitail.ia@gmail.com"
                    className="text-sm text-gray-600 hover:text-[#F4791F] transition-colors break-all"
                  >
                    abidjandigitail.ia@gmail.com
                  </a>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <div className="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'rgba(79,181,43,0.12)' }}>
                  <MapPin size={18} style={{ color: '#4FB52B' }} />
                </div>
                <div>
                  <p className="font-semibold text-sm mb-1" style={{ color: '#0E2A63' }}>🇺🇸 États-Unis</p>
                  <p className="text-sm text-gray-600">Fort Worth, Texas</p>
                </div>
              </div>

              <div className="flex items-start gap-3">
                <div className="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style={{ backgroundColor: 'rgba(79,181,43,0.12)' }}>
                  <MapPin size={18} style={{ color: '#4FB52B' }} />
                </div>
                <div>
                  <p className="font-semibold text-sm mb-1" style={{ color: '#0E2A63' }}>🇨🇮 Côte d'Ivoire</p>
                  <p className="text-sm text-gray-600">Abidjan (Cocody)</p>
                </div>
              </div>

              <div className="mt-8 p-4 rounded-xl text-sm text-blue-800" style={{ backgroundColor: 'rgba(14,42,99,0.06)' }}>
                <p className="font-semibold mb-1">Délai de réponse</p>
                <p className="text-gray-600">Nous répondons généralement dans les <strong>24 heures</strong> ouvrées.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
