import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { GUIDES, getGuide } from '@/lib/guides';
import GuideContent from './GuideContent';

export const dynamicParams = false;

export function generateStaticParams() {
  return GUIDES.map(g => ({ slug: g.slug }));
}

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }): Promise<Metadata> {
  const { slug } = await params;
  const guide = getGuide(slug);
  if (!guide) return {};
  return {
    title: `${guide.title.fr} — Guides gratuits Abidjan Digital.ia`,
    description: guide.description.fr,
    openGraph: {
      title: guide.title.fr,
      description: guide.description.fr,
      type: 'article',
      locale: 'fr_FR',
    },
  };
}

export default async function GuidePage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  if (!getGuide(slug)) notFound();
  return <GuideContent slug={slug} />;
}
