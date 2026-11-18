// src/lib/projects.config.ts
// Liens, images et catégories des projets de la page /projets (même ordre que t.projets.items).
// Partagé entre la page Projets et la base de connaissances du chatbot Adia.

export type ProjectCategoryId = 'siteWeb' | 'infographie' | 'formation';

export const PROJECT_CATEGORY_IDS: ProjectCategoryId[] = ['siteWeb', 'infographie', 'formation'];

export interface ProjectMeta {
  categoryId: ProjectCategoryId;
  image?: string;
  link?: string;
  /** 'contain' = image affichée en entier (affiches, visuels) ; 'cover' (défaut) = recadrée */
  fit?: 'cover' | 'contain';
}

export const PROJECT_META: ProjectMeta[] = [
  { categoryId: 'siteWeb', image: '/projets/carnet-djassa.jpg', link: 'https://carnet-djassa.vercel.app' },
  { categoryId: 'siteWeb', image: '/projets/mon-commerce.jpg', link: 'https://livraison-app-two.vercel.app' },
  { categoryId: 'siteWeb', image: '/projets/pharmagarde-ci.jpg', link: 'https://pharmagarde-ci.vercel.app' },
  { categoryId: 'siteWeb', image: '/projets/kaman-chantier.jpg', link: 'https://kaman-chantier.vercel.app' },
  { categoryId: 'siteWeb', image: '/projets/ma-tontine.jpg', link: 'https://ma-tontine-d2a32.web.app' },
  { categoryId: 'formation', image: '/realisations/formation-ia.jpg' },
  { categoryId: 'infographie', image: '/realisations/bohobli-skin-care.jpg', fit: 'contain' },
  { categoryId: 'infographie', image: '/realisations/business-ia-kit.jpg', fit: 'contain' },
];
