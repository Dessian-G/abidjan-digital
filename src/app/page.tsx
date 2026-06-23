import Link from 'next/link';
import { GraduationCap, Image as ImageIcon, Globe, CheckCircle, MapPin, Zap, Users } from 'lucide-react';
import NetworkBackground from '@/components/NetworkBackground';
import TypewriterText from '@/components/TypewriterText';
import PulseLink from '@/components/PulseLink';

const services = [
  {
    icon: GraduationCap,
    title: 'Formations digitales & IA',
    description:
      "Apprenez à maîtriser les outils numériques et l'intelligence artificielle pour développer votre activité, même sans formation technique.",
    color: '#4FB52B',
    href: '/services#formations',
  },
  {
    icon: ImageIcon,
    title: "Création d'infographies",
    description:
      'Des visuels professionnels et percutants pour vos réseaux sociaux et supports de communication qui reflètent votre marque.',
    color: '#F4791F',
    href: '/services#infographies',
  },
  {
    icon: Globe,
    title: 'Création de sites web',
    description:
      'Sites vitrines, e-commerce ou landing pages : nous concevons votre présence en ligne, de A à Z, rapide et moderne.',
    color: '#0E2A63',
    href: '/services#sites-web',
  },
];

const reasons = [
  {
    icon: Zap,
    title: 'Expertise IA & Digital',
    text: "Nous intégrons les derniers outils d'intelligence artificielle pour des résultats concrets et différenciants.",
  },
  {
    icon: MapPin,
    title: 'Double implantation',
    text: "Présents à Fort Worth (Texas) et à Abidjan (Cocody), nous comprenons les réalités de nos clients d'Afrique et de la diaspora.",
  },
  {
    icon: Users,
    title: 'Accompagnement personnalisé',
    text: 'Chaque projet est unique. Nous prenons le temps de comprendre votre besoin avant de proposer une solution adaptée.',
  },
  {
    icon: CheckCircle,
    title: 'Contenu de qualité',
    text: 'Que ce soit pour votre site, vos visuels ou vos formations, nous livrons un travail soigné et professionnel.',
  },
];

export default function HomePage() {
  return (
    <>
      {/* HERO */}
      <section className="relative overflow-hidden py-14 sm:py-20 md:py-28 px-4 sm:px-6" style={{ backgroundColor: '#0E2A63' }}>
        <NetworkBackground />
        <div className="absolute inset-0 opacity-10"
          style={{
            backgroundImage: 'radial-gradient(circle at 20% 50%, #4FB52B 0%, transparent 50%), radial-gradient(circle at 80% 20%, #F4791F 0%, transparent 50%)',
          }}
        />
        <div className="relative max-w-4xl mx-auto text-center text-white">
          <div className="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-6 tracking-wide"
            style={{ backgroundColor: 'rgba(79,181,43,0.2)', color: '#7be84f', border: '1px solid rgba(79,181,43,0.4)' }}>
            🌍 Fort Worth · Abidjan — Studio Web & IA
          </div>
          <h1 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight mb-4">
            Votre allié digital & IA,<br />
            <TypewriterText
              texts={["d'Abidjan à Fort Worth", "pour les entrepreneurs", "propulsé par l'IA"]}
              style={{ color: '#F4791F' }}
            />
          </h1>
          <p className="text-lg md:text-xl font-medium mb-3" style={{ color: '#c8d8ff' }}>
            Apprenez. Automatisez. Réussissez.
          </p>
          <p className="text-sm sm:text-base md:text-lg text-blue-200 mb-8 sm:mb-10 max-w-2xl mx-auto px-2">
            Formations digitales, infographies professionnelles et création de sites web — nous équipons les entrepreneurs francophones pour réussir à l'ère du numérique.
          </p>
          <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4 sm:px-0">
            <PulseLink
              href="/contact"
              className="px-7 py-3 rounded-xl font-semibold text-white shadow-lg transition-transform hover:scale-105 text-sm sm:text-base text-center"
              style={{ backgroundColor: '#F4791F' }}
            >
              Demander un service
            </PulseLink>
            <Link
              href="/services"
              className="px-7 py-3 rounded-xl font-semibold border-2 border-white text-white transition-colors hover:bg-white hover:text-[#0E2A63] text-sm sm:text-base text-center"
            >
              Voir nos services
            </Link>
          </div>
        </div>
      </section>

      {/* SERVICES */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8 sm:mb-12">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              Nos services
            </h2>
            <p className="text-gray-500 max-w-xl mx-auto">
              Trois expertises complémentaires pour accélérer votre développement digital.
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            {services.map(({ icon: Icon, title, description, color, href }) => (
              <Link
                key={title}
                href={href}
                className="group bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1"
              >
                <div className="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style={{ backgroundColor: `${color}15` }}>
                  <Icon size={24} style={{ color }} />
                </div>
                <h3 className="font-bold text-lg mb-2" style={{ color: '#0E2A63' }}>{title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{description}</p>
                <span className="inline-block mt-4 text-sm font-semibold transition-colors group-hover:underline" style={{ color: '#F4791F' }}>
                  En savoir plus →
                </span>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* POURQUOI NOUS */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8 sm:mb-12">
            <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
              Pourquoi nous choisir ?
            </h2>
            <p className="text-gray-500 max-w-xl mx-auto">
              Un studio pensé pour les entrepreneurs francophones d'Afrique et de la diaspora.
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            {reasons.map(({ icon: Icon, title, text }) => (
              <div key={title} className="flex gap-3 sm:gap-4 bg-white rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-sm">
                <div className="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style={{ backgroundColor: 'rgba(79,181,43,0.12)' }}>
                  <Icon size={20} style={{ color: '#4FB52B' }} />
                </div>
                <div>
                  <h3 className="font-bold mb-1" style={{ color: '#0E2A63' }}>{title}</h3>
                  <p className="text-gray-500 text-sm leading-relaxed">{text}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* PROJETS PREVIEW */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto text-center">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            Nos projets
          </h2>
          <p className="text-gray-500 max-w-xl mx-auto mb-8 sm:mb-10">
            Decouvrez nos realisations et les projets sur lesquels nous travaillons.
          </p>
          <div className="rounded-2xl border border-gray-100 p-8 sm:p-12 mb-8" style={{ backgroundColor: '#f7f9ff' }}>
            <p className="text-gray-400 text-sm sm:text-base mb-4">
              Nos projets arrivent bientot — restez connectes !
            </p>
          </div>
          <Link
            href="/projets"
            className="inline-block px-6 py-3 rounded-xl font-semibold text-sm sm:text-base transition-transform hover:scale-105 border-2"
            style={{ color: '#0E2A63', borderColor: '#0E2A63' }}
          >
            Voir tous les projets
          </Link>
        </div>
      </section>

      {/* CTA FINAL */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#F4791F' }}>
        <div className="max-w-2xl mx-auto">
          <h2 className="text-xl sm:text-2xl md:text-3xl font-extrabold mb-4">
            Prêt à passer à l'action ?
          </h2>
          <p className="text-orange-100 mb-8 text-base">
            Décrivez-nous votre projet en quelques lignes — nous vous répondons rapidement.
          </p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-[#F4791F] bg-white shadow-lg hover:shadow-xl transition-transform hover:scale-105 text-base"
          >
            Remplir le formulaire de demande
          </Link>
        </div>
      </section>
    </>
  );
}
