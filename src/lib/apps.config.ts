// src/lib/apps.config.ts
// Config centralisée du portfolio d'applications Abidjan Digital.ia
// Pour ajouter une app : ajoute un objet ici, rien d'autre à toucher.

export type AppStatus = 'en-ligne' | 'beta' | 'bientot';

export type AppCategory =
  | 'Commerce'
  | 'Finance'
  | 'Agriculture'
  | 'Éducation'
  | 'Communauté'
  | 'Outils';

// Numéro WhatsApp par défaut (format international sans + ni espaces)
export const WHATSAPP_NUMBER = '12147932405';

export interface PortfolioApp {
  id: string;
  whatsapp?: string; // numéro dédié à cette app (sinon WHATSAPP_NUMBER)
  nom: string;
  tagline: string;
  description: string;
  categorie: AppCategory;
  statut: AppStatus;
  url: string;
  couleur: string;
  initiales: string;
  logo?: string;
}

export const APPS: PortfolioApp[] = [
  {
    id: 'carnet-djassa',
    nom: 'Carnet Djassa',
    tagline: 'Le carnet de crédit de ta boutique, dans ta poche.',
    description:
      'Remplace le cahier manuel : enregistre les crédits clients, reçois des rappels et suis tes encaissements en temps réel.',
    categorie: 'Finance',
    statut: 'en-ligne',
    url: 'https://carnet-djassa.vercel.app',
    couleur: '#E8730C',
    initiales: 'CD',
  },
  {
    id: 'commerce-ivoire',
    nom: 'Commerce Ivoire',
    tagline: "Tous les commerces d'Abidjan, triés par proximité.",
    description:
      "Annuaire des commerces du District d'Abidjan avec recherche par quartier et mise en relation pour la livraison.",
    categorie: 'Commerce',
    statut: 'bientot',
    url: '#',
    couleur: '#0E7C3A',
    initiales: 'CI',
  },
  {
    id: 'agriconnect',
    nom: 'AgriConnect Afrique',
    tagline: 'Le marché, la météo et les conseils IA pour les planteurs.',
    description:
      "Prix du marché par région, alertes météo et conseils agricoles assistés par IA, pensés pour la Côte d'Ivoire.",
    categorie: 'Agriculture',
    statut: 'beta',
    url: 'https://agriconnect-afrique.vercel.app',
    couleur: '#4C8C2B',
    initiales: 'AC',
  },
  {
    id: 'gestion-associations',
    nom: 'Ma Communauté',
    tagline: 'Gère ton association sans tableur ni papier.',
    description:
      'Membres, cotisations, événements et notifications WhatsApp — la PWA de gestion pour associations et mutuelles.',
    categorie: 'Communauté',
    statut: 'bientot',
    url: '#',
    couleur: '#7C3AED',
    initiales: 'MC',
  },
  {
    id: 'kaman-chantier',
    nom: 'Kaman Chantier',
    tagline: "Suis les dépenses de ton chantier depuis l'étranger.",
    description:
      'Pour la diaspora qui construit au pays : chaque dépense justifiée par photo, chaque franc tracé.',
    categorie: 'Outils',
    statut: 'en-ligne',
    url: 'https://kaman-chantier.vercel.app',
    couleur: '#B45309',
    initiales: 'KC',
  },
  {
    id: 'revision-hors-ligne',
    nom: 'Révision+',
    tagline: 'Fiches et quiz qui marchent même sans connexion.',
    description:
      'PWA de révision hors ligne pour les élèves : fiches, quiz et suivi de progression stockés sur le téléphone.',
    categorie: 'Éducation',
    statut: 'bientot',
    url: '#',
    couleur: '#2563EB',
    initiales: 'R+',
  },
  {
    id: 'ma-tontine',
    nom: 'Ma Tontine',
    tagline: 'Organisez et suivez les tontines de votre groupe, sans tableur.',
    description:
      'Gestion des cotisations, des tours de tontine et des membres, avec suivi des paiements en temps réel.',
    categorie: 'Finance',
    statut: 'en-ligne',
    url: 'https://ma-tontine-d2a32.web.app',
    couleur: '#0891B2',
    initiales: 'MT',
  },
  {
    id: 'royal-ministry',
    nom: 'Royal Ministry',
    tagline: 'Gérez les membres et activités de votre organisation religieuse, simplement.',
    description:
      'Suivi des membres, des dons/offrandes et des événements pour paroisses, ministères et organisations religieuses.',
    categorie: 'Communauté',
    statut: 'en-ligne',
    url: 'https://royal-ministry.vercel.app',
    couleur: '#B8860B',
    initiales: 'RM',
  },
  {
    id: 'abidjan-circul',
    nom: "Abidjan Circul'",
    tagline: "L'état du trafic à Abidjan, pont par pont, en temps réel.",
    description:
      "Circulation en direct dans les 13 communes et sur les 4 ponts du District d'Abidjan, avec signalements communautaires et conseil de départ.",
    categorie: 'Outils',
    statut: 'en-ligne',
    url: 'https://abidjan-circul.vercel.app',
    couleur: '#1CA0A6',
    initiales: 'AB',
  },
];

export const CATEGORIES: AppCategory[] = [
  'Commerce',
  'Finance',
  'Agriculture',
  'Éducation',
  'Communauté',
  'Outils',
];

export const STATUT_LABELS: Record<AppStatus, string> = {
  'en-ligne': 'En ligne',
  beta: 'Bêta',
  bientot: 'Bientôt',
};
