'use client';

import Link from 'next/link';
import { useState } from 'react';
import { ExternalLink, Globe, Image as ImageIcon, GraduationCap, Layers } from 'lucide-react';
import { useLanguage } from '@/context/LanguageContext';
import { Translations } from '@/lib/i18n';
import { PROJECT_CATEGORY_IDS as CATEGORY_IDS, PROJECT_META, ProjectCategoryId as CategoryId } from '@/lib/projects.config';

const CATEGORY_ICONS: Record<CategoryId, typeof Globe> = {
  siteWeb: Globe,
  infographie: ImageIcon,
  formation: GraduationCap,
};

const CATEGORY_COLORS: Record<CategoryId, string> = {
  siteWeb: '#0E2A63',
  infographie: '#F4791F',
  formation: '#4FB52B',
};

interface Project {
  title: string;
  categoryId: CategoryId;
  categoryLabel: string;
  description: string;
  image?: string;
  link?: string;
  fit?: 'cover' | 'contain';
  tags: string[];
}

function EmptyState({ t }: { t: Translations }) {
  return (
    <div className="text-center py-16 sm:py-24 px-4">
      <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center mx-auto mb-6" style={{ backgroundColor: 'rgba(14,42,99,0.06)' }}>
        <Layers size={36} className="sm:hidden" style={{ color: '#0E2A63' }} />
        <Layers size={44} className="hidden sm:block" style={{ color: '#0E2A63' }} />
      </div>
      <h3 className="text-lg sm:text-xl font-bold mb-2" style={{ color: '#0E2A63' }}>
        {t.projets.emptyTitle}
      </h3>
      <p className="text-gray-500 max-w-md mx-auto text-sm sm:text-base mb-8">
        {t.projets.emptyText}
      </p>
      <Link
        href="/contact"
        className="inline-block px-6 py-3 rounded-xl font-semibold text-white text-sm sm:text-base transition-transform hover:scale-105"
        style={{ backgroundColor: '#F4791F' }}
      >
        {t.projets.emptyButton}
      </Link>
    </div>
  );
}

function ProjectCard({ project, viewProjectLabel }: { project: Project; viewProjectLabel: string }) {
  const Icon = CATEGORY_ICONS[project.categoryId] ?? Globe;
  const color = CATEGORY_COLORS[project.categoryId] ?? '#0E2A63';

  return (
    <div className="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 overflow-hidden flex flex-col">
      {project.image ? (
        <div className="relative w-full h-56 sm:h-60 bg-gray-100 overflow-hidden">
          {/* Sans lien externe, l'image s'ouvre en grand dans un nouvel onglet */}
          <a
            href={project.link ?? project.image}
            target="_blank"
            rel="noopener noreferrer"
            className="block w-full h-full"
            style={project.fit === 'contain' ? { backgroundColor: '#0E2A63' } : undefined}
          >
            <img
              src={project.image}
              alt={project.title}
              loading="lazy"
              className={`w-full h-full ${project.fit === 'contain' ? 'object-contain' : 'object-cover'} ${project.categoryId === 'siteWeb' ? 'object-top' : ''} group-hover:scale-105 transition-transform duration-300`}
            />
          </a>
          <span
            className="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold text-white"
            style={{ backgroundColor: color }}
          >
            {project.categoryLabel}
          </span>
        </div>
      ) : (
        <div className="relative w-full h-48 sm:h-52 flex items-center justify-center" style={{ backgroundColor: `${color}08` }}>
          <Icon size={48} style={{ color, opacity: 0.3 }} />
          <span
            className="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold text-white"
            style={{ backgroundColor: color }}
          >
            {project.categoryLabel}
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
            {viewProjectLabel} <ExternalLink size={14} />
          </a>
        )}
      </div>
    </div>
  );
}

export default function ProjetsContent() {
  const { t } = useLanguage();
  const [filter, setFilter] = useState<'all' | CategoryId>('all');

  const projects: Project[] = t.projets.items.map((item, i) => ({
    ...item,
    ...PROJECT_META[i],
    categoryLabel: t.projets.categories[PROJECT_META[i].categoryId],
  }));

  return (
    <>
      {/* HERO */}
      <section className="py-12 sm:py-16 px-4 sm:px-6 text-white text-center" style={{ backgroundColor: '#0E2A63' }}>
        <div className="max-w-3xl mx-auto">
          <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-4">{t.projets.heroTitle}</h1>
          <p className="text-blue-200 text-base sm:text-lg px-2">
            {t.projets.heroSubtitle}
          </p>
        </div>
      </section>

      {/* PROJECTS GRID */}
      <section className="py-12 sm:py-16 md:py-20 px-4 sm:px-6 bg-white">
        <div className="max-w-5xl mx-auto">
          {projects.length === 0 ? (
            <EmptyState t={t} />
          ) : (
            <>
              {/* Filtres par catégorie */}
              <div className="flex flex-wrap gap-2 mb-8 sm:mb-10 justify-center">
                {(['all', ...CATEGORY_IDS] as const).map(id => {
                  const active = filter === id;
                  const label = id === 'all' ? t.projets.categoryAll : t.projets.categories[id];
                  const count = id === 'all' ? projects.length : projects.filter(p => p.categoryId === id).length;
                  return (
                    <button
                      key={id}
                      type="button"
                      onClick={() => setFilter(id)}
                      aria-pressed={active}
                      className="px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-colors border"
                      style={
                        active
                          ? { backgroundColor: '#0E2A63', color: '#fff', borderColor: '#0E2A63' }
                          : { color: '#0E2A63', borderColor: '#e5e7eb', backgroundColor: '#fff' }
                      }
                    >
                      {label} <span className={active ? 'opacity-80' : 'text-gray-400'}>{count}</span>
                    </button>
                  );
                })}
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {projects.filter(p => filter === 'all' || p.categoryId === filter).map(project => (
                  <ProjectCard key={project.title} project={project} viewProjectLabel={t.projets.viewProject} />
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
            {t.projets.ctaTitle}
          </h2>
          <p className="text-gray-500 mb-6 text-sm sm:text-base">
            {t.projets.ctaText}
          </p>
          <Link
            href="/contact"
            className="inline-block px-8 py-3 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-transform hover:scale-105 text-sm sm:text-base"
            style={{ backgroundColor: '#F4791F' }}
          >
            {t.projets.ctaButton}
          </Link>
        </div>
      </section>
    </>
  );
}
