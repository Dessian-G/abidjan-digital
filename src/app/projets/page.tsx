import type { Metadata } from 'next';
import Link from 'next/link';
import { ExternalLink, Globe, Image as ImageIcon, GraduationCap, Layers } from 'lucide-react';

export const metadata: Metadata = {
  title: 'Projets — Abidjan Digital.ia',
  description:
    "Découvrez les projets réalisés par Abidjan Digital.ia : sites web, infographies et formations digitales & IA.",
};

const CATEGORY_ICONS: Record<string, typeof Globe> = {
  'Site web': Globe,
  'Infographie': ImageIcon,
  'Formation': GraduationCap,
};

const CATEGORY_COLORS: Record<string, string> = {
  'Site web': '#0E2A63',
  'Infographie': '#F4791F',
  'Formation': '#4FB52B',
};

interface Project {
  title: string;
  category: string;
  description: string;
  image?: string;
  link?: string;
  tags: string[];
}

const projects: Project[] = [
  // --- Ajoutez vos projets ici ---
  // Exemple :
  // {
  //   title: 'Boutique Awa Fashion',
  //   category: 'Site web',
  //   description: 'Site e-commerce pour une marque de mode africaine basée à Abidjan.',
  //   image: '/projets/awa-fashion.jpg',
  //   link: 'https://awa-fashion.com',
  //   tags: ['E-commerce', 'Next.js', 'Stripe'],
  // },
];

function EmptyState() {
  return (
    <div className="text-center py-16 sm:py-24 px-4">
      <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-6" style={{ backgroundColor: 'rgba(14,42,99,0.06)' }}>
        <Layers size={36} className="sm:hidden" style={{ color: '#0E2A63' }} />
        <Layers size={44} className="hidden sm:block" style={{ color: '#0E2A63' }} />
      </div>
      <h3 className="text-lg sm:text-xl font-bold mb-2" style={{ color: '#0E2A63' }}>
        Nos projets arrivent bientot
      </h3>
      <p className="text-gray-500 max-w-md mx-auto text-sm sm:text-base mb-8">
        Nous travaillons actuellement sur plusieurs projets passionnants. Revenez bientot pour decouvrir nos realisations !
      </p>
      <Link
        href="/contact"
        className="inline-block px-6 py-3 rounded-xl font-semibold text-white text-sm sm:text-base transition-transform hover:scale-105"
        style={{ backgroundColor: '#F4791F' }}
      >
        Discutons de votre projet
      </Link>
    </div>
  );
}

function ProjectCard({ project }: { project: Project }) {
  const Icon = CATEGORY_ICONS[project.category] ?? Globe;
  const color = CATEGORY_COLORS[project.category] ?? '#0E2A63';

  return (
    <div className="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 overflow-hidden flex flex-col">
      {project.image ? (
        <div className="relative w-full h-48 sm:h-52 bg-gray-100 overflow-hidden">
          <img
            src={project.image}
            alt={project.title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
          <span
            className="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold text-white"
            style={{ backgroundColor: color }}
          >
            {project.category}
          </span>
        </div>
      ) : (
        <div className="relative w-full h-48 sm:h-52 flex items-center justify-center" style={{ backgroundColor: `${color}08` }}>
          <Icon size={48} style={{ color, opacity: 0.3 }} />
          <span
            className="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold text-white"
            style={{ backgroundColor: color }}
          >
            {project.category}
          </span>
        </div>
      )}

      <div className="p-5 sm:p-6 flex flex-col flex-1">
        <h3 className="font-bold text-base sm:text-lg mb-2" style={{ color: '#0E2A63' }}>
          {project.title}
        </h3>
        <p className="text-gray-500 text-sm leading-relaxed mb-4 flex-1">
          {project.description}
        </p>

        <div className="flex flex-wrap gap-1.5 mb-4">
          {project.tags.map(tag => (
            <span
              key={tag}
              className="px-2.5 py-0.5 rounded-full text-xs font-medium border"
              style={{ color, borderColor: `${color}30`, backgroundColor: `${color}08` }}
            >
              {tag}
            </span>
          ))}
        </div>

        {project.link && (
          <a
            href={project.link}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:underline"
            style={{ color: '#F4791F' }}
          >
            Voir le projet <ExternalLink size={14} />
          </a>
        )}
      </div>
    </div>
  );
}

export default function ProjetsPage() {
  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">Nos projets</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            Decouvrez nos realisations : sites web, infographies et formations pour entrepreneurs.
          </p>
        </div>
      </section>

      {/* PROJECTS GRID */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          {projects.length === 0 ? (
            <EmptyState />
          ) : (
            <>
              {/* Filter tabs — ready for future use */}
              <div className="flex flex-wrap gap-2 mb-8 sm:mb-10 justify-center">
                {['Tous', ...Object.keys(CATEGORY_ICONS)].map(cat => (
                  <span
                    key={cat}
                    className="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold cursor-pointer transition-colors border"
                    style={
                      cat === 'Tous'
                        ? { backgroundColor: '#0E2A63', color: '#fff', borderColor: '#0E2A63' }
                        : { color: '#0E2A63', borderColor: '#e5e7eb', backgroundColor: '#fff' }
                    }
                  >
                    {cat}
                  </span>
                ))}
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {projects.map(project => (
                  <ProjectCard key={project.title} project={project} />
                ))}
              </div>
            </>
          )}
        </div>
      </section>

      {/* CTA */}
      <section className="py-10 sm:py-14 px-4 sm:px-6 text-center" style={{ backgroundColor: '#f7f9ff' }}>
        <div className="max-w-xl mx-auto">
          <h2 className="text-xl sm:text-2xl font-extrabold mb-3" style={{ color: '#0E2A63' }}>
            Vous avez un projet ?
          </h2>
          <p className="text-gray-500 mb-6 text-sm sm:text-base">
            Parlez-nous de votre idee — nous la transformons en realite digitale.
          </p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105 text-sm sm:text-base"
            style={{ backgroundColor: '#F4791F' }}
          >
            Demander un devis
          </Link>
        </div>
      </section>
    </>
  );
}
