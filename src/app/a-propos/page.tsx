import type { Metadata } from 'next';
import Image from 'next/image';
import Link from 'next/link';
import { MapPin, Target, Heart, Lightbulb } from 'lucide-react';

export const metadata: Metadata = {
  title: 'À propos — Abidjan Digital.ia',
  description:
    "Découvrez l'histoire et la mission d'Abidjan Digital.ia, studio web & IA fondé par Max Guiro, présent à Fort Worth et Abidjan.",
};

const values = [
  {
    icon: Target,
    title: 'Notre mission',
    text: "Rendre le digital et l'intelligence artificielle accessibles aux entrepreneurs francophones et en faire un véritable levier de croissance.",
  },
  {
    icon: Heart,
    title: 'Nos valeurs',
    text: 'Accessibilité, excellence et proximité. Nous croyons que chaque entrepreneur mérite un accompagnement de qualité, quelle que soit sa localisation.',
  },
  {
    icon: Lightbulb,
    title: 'Notre vision',
    text: "Un monde où les entrepreneurs d'Afrique et de la diaspora utilisent les meilleures technologies pour créer, innover et prospérer.",
  },
];

export default function AProposPage() {
  return (
    <>
      {/* HERO */}
      <section className="py-16 px-4 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-3xl md:text-4xl font-extrabold mb-4">À propos de nous</h1>
          <p className="text-blue-200 text-lg">
            Un studio digital & IA au service des entrepreneurs francophones, d'Abidjan à Fort Worth.
          </p>
        </div>
      </section>

      {/* MISSION & VALEURS */}
      <section className="py-20 px-4 bg-white">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              Qui sommes-nous ?
            </h2>
            <p className="text-gray-500 max-w-2xl mx-auto leading-relaxed">
              Abidjan Digital.ia est un studio web & intelligence artificielle fondé avec une conviction forte : le numérique doit être un outil de croissance pour tous les entrepreneurs francophones, qu'ils soient en Afrique de l'Ouest ou dans la diaspora.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {values.map(({ icon: Icon, title, text }) => (
              <div key={title} className="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <div className="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style={{ backgroundColor: 'rgba(14,42,99,0.08)' }}>
                  <Icon size={22} style={{ color: '#0E2A63' }} />
                </div>
                <h3 className="font-bold mb-2" style={{ color: '#0E2A63' }}>{title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{text}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* FONDATEUR */}
      <section className="py-20 px-4" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-4xl mx-auto">
          <h2 className="text-2xl md:text-3xl font-extrabold mb-10 text-center" style={{ color: '#0E2A63' }}>
            Le fondateur
          </h2>
          <div className="flex flex-col md:flex-row gap-10 items-center">
            <div className="shrink-0">
              <Image
                src="/max-guiro.jpg"
                alt="Max Guiro, fondateur d'Abidjan Digital.ia"
                width={240}
                height={300}
                className="rounded-2xl object-cover shadow-lg"
                style={{ width: '240px', height: '300px' }}
              />
            </div>
            <div>
              <h3 className="text-xl font-extrabold mb-1" style={{ color: '#0E2A63' }}>Max Guiro</h3>
              <p className="text-sm font-semibold mb-4" style={{ color: '#F4791F' }}>Fondateur d'Abidjan Digital.ia</p>
              <div className="space-y-3 text-gray-600 leading-relaxed text-sm">
                <p>
                  Max Guiro est le fondateur d'Abidjan Digital.ia. Entrepreneur passionné par le numérique et l'intelligence artificielle, il a créé l'agence avec une conviction simple : ces technologies doivent être accessibles aux entrepreneurs francophones et devenir un véritable levier de croissance.
                </p>
                <p>
                  Depuis ses bureaux de <strong>Fort Worth (Texas)</strong> et d'<strong>Abidjan (Cocody)</strong>, il accompagne porteurs de projets et entreprises dans leur transformation digitale — de la formation à la création de sites web, en passant par le design et l'automatisation.
                </p>
                <p>
                  Sa mission tient en trois mots : <strong className="italic" style={{ color: '#0E2A63' }}>apprendre, automatiser, réussir.</strong>
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* DOUBLE IMPLANTATION */}
      <section className="py-20 px-4 bg-white">
        <div className="max-w-4xl mx-auto text-center">
          <h2 className="text-2xl md:text-3xl font-extrabold mb-4" style={{ color: '#0E2A63' }}>
            Notre présence internationale
          </h2>
          <p className="text-gray-500 mb-10 max-w-xl mx-auto">
            Une double implantation stratégique pour mieux servir nos clients d'Afrique et de la diaspora.
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div className="rounded-2xl p-8 border-2 text-left" style={{ borderColor: '#4FB52B', backgroundColor: 'rgba(79,181,43,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <span className="text-2xl">🇺🇸</span>
                <div>
                  <p className="font-bold text-lg" style={{ color: '#0E2A63' }}>Fort Worth, Texas</p>
                  <p className="text-sm text-gray-400">États-Unis</p>
                </div>
              </div>
              <div className="flex items-start gap-2 text-sm text-gray-600">
                <MapPin size={15} className="mt-0.5 shrink-0" style={{ color: '#4FB52B' }} />
                <p>Bureau principal pour les clients de la diaspora et du marché américain.</p>
              </div>
            </div>
            <div className="rounded-2xl p-8 border-2 text-left" style={{ borderColor: '#F4791F', backgroundColor: 'rgba(244,121,31,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <span className="text-2xl">🇨🇮</span>
                <div>
                  <p className="font-bold text-lg" style={{ color: '#0E2A63' }}>Abidjan, Cocody</p>
                  <p className="text-sm text-gray-400">Côte d'Ivoire</p>
                </div>
              </div>
              <div className="flex items-start gap-2 text-sm text-gray-600">
                <MapPin size={15} className="mt-0.5 shrink-0" style={{ color: '#F4791F' }} />
                <p>Présence locale pour accompagner les entrepreneurs d'Afrique de l'Ouest.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-14 px-4 text-center text-white" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-2xl font-extrabold mb-3">Travaillons ensemble</h2>
          <p className="text-blue-200 mb-6">Vous avez un projet digital ? Décrivez-le nous, nous vous répondrons rapidement.</p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-[#0E2A63] bg-white shadow-md hover:shadow-lg transition-transform hover:scale-105"
          >
            Nous contacter
          </Link>
        </div>
      </section>
    </>
  );
}
