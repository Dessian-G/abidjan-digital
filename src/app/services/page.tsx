import type { Metadata } from 'next';
import Link from 'next/link';
import { GraduationCap, Image as ImageIcon, Globe, CheckCircle } from 'lucide-react';

export const metadata: Metadata = {
  title: 'Services — Abidjan Digital.ia',
  description:
    "Découvrez nos services : formations digitales & IA, création d'infographies professionnelles et création de sites web sur mesure.",
};

const services = [
  {
    id: 'formations',
    icon: GraduationCap,
    color: '#4FB52B',
    bg: 'rgba(79,181,43,0.08)',
    title: 'Formations digitales & IA',
    intro:
      "Nous vous accompagnons pas à pas pour maîtriser les outils numériques et l'intelligence artificielle, même sans bagage technique.",
    benefits: [
      'Formations adaptées à votre niveau et à votre secteur',
      "Outils IA concrets : ChatGPT, automatisation, création de contenu",
      'Prise en main rapide, résultats immédiats',
      'Suivi personnalisé après la formation',
    ],
    deliverables: ['Sessions individuelles ou en groupe', 'Supports pédagogiques fournis', 'Accès à des ressources exclusives'],
  },
  {
    id: 'infographies',
    icon: ImageIcon,
    color: '#F4791F',
    bg: 'rgba(244,121,31,0.08)',
    title: "Création d'infographies",
    intro:
      'Des visuels professionnels, percutants et adaptés à votre identité de marque — pour vos réseaux sociaux, présentations et supports de communication.',
    benefits: [
      'Design moderne et cohérent avec votre image de marque',
      'Formats adaptés à chaque plateforme (Instagram, LinkedIn, Facebook…)',
      'Livraison rapide en haute définition',
      'Révisions incluses',
    ],
    deliverables: ['Infographies pour réseaux sociaux', 'Présentations visuelles', 'Bannières et affiches', 'Carrousels et stories'],
  },
  {
    id: 'sites-web',
    icon: Globe,
    color: '#0E2A63',
    bg: 'rgba(14,42,99,0.08)',
    title: 'Création de sites web',
    intro:
      'Votre vitrine digitale sur mesure : site professionnel, e-commerce ou landing page — rapide, moderne et optimisé pour convertir vos visiteurs en clients.',
    benefits: [
      'Design sur mesure, adapté à votre secteur',
      '100 % responsive (mobile, tablette, desktop)',
      'Optimisé pour le référencement (SEO)',
      "Formulaire de contact et intégration d'outils (CRM, email…)",
    ],
    deliverables: ['Site vitrine professionnel', 'Boutique en ligne (e-commerce)', 'Landing page de conversion', 'Refonte de site existant'],
  },
];

export default function ServicesPage() {
  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">Nos services</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            Trois expertises pour accompagner votre développement digital, de la formation à la création.
          </p>
        </div>
      </section>

      {/* SERVICES */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto space-y-12 sm:space-y-16">
          {services.map(({ id, icon: Icon, color, bg, title, intro, benefits, deliverables }, idx) => (
            <div
              key={id}
              id={id}
              className={`flex flex-col ${idx % 2 !== 0 ? 'md:flex-row-reverse' : 'md:flex-row'} gap-6 sm:gap-8 items-start`}
            >
              {/* Visual card */}
              <div
                className="w-full md:w-2/5 rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center text-center shrink-0"
                style={{ backgroundColor: bg, minHeight: '180px' }}
              >
                <div className="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-sm" style={{ backgroundColor: `${color}20` }}>
                  <Icon size={32} style={{ color }} />
                </div>
                <h2 className="text-xl font-extrabold" style={{ color: '#0E2A63' }}>{title}</h2>
              </div>

              {/* Content */}
              <div className="flex-1">
                <p className="text-gray-600 leading-relaxed mb-6">{intro}</p>

                <div className="mb-6">
                  <h3 className="font-bold mb-3" style={{ color: '#0E2A63' }}>Bénéfices</h3>
                  <ul className="space-y-2">
                    {benefits.map(b => (
                      <li key={b} className="flex items-start gap-2 text-sm text-gray-600">
                        <CheckCircle size={16} className="mt-0.5 shrink-0" style={{ color: '#4FB52B' }} />
                        {b}
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="mb-6">
                  <h3 className="font-bold mb-3" style={{ color: '#0E2A63' }}>Exemples de livrables</h3>
                  <div className="flex flex-wrap gap-2">
                    {deliverables.map(d => (
                      <span key={d} className="px-3 py-1 rounded-full text-xs font-medium border" style={{ color, borderColor: color, backgroundColor: bg }}>
                        {d}
                      </span>
                    ))}
                  </div>
                </div>

                <Link
                  href={`/contact?service=${id}`}
                  className="inline-block px-6 py-2.5 rounded-xl font-semibold text-sm text-white transition-transform hover:scale-105"
                  style={{ backgroundColor: '#F4791F' }}
                >
                  Demander ce service
                </Link>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>Un projet en tête ?</h2>
          <p className="text-gray-500 mb-6">Dites-nous ce dont vous avez besoin — nous vous répondrons sous 24h.</p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105"
            style={{ backgroundColor: '#F4791F' }}
          >
            Remplir le formulaire de demande
          </Link>
        </div>
      </section>
    </>
  );
}
