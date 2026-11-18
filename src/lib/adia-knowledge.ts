// src/lib/adia-knowledge.ts
// Base de connaissances du chatbot Adia, générée à partir du contenu du site
// (traductions, applications, projets, guides). Rien à mettre à jour ici :
// quand un texte, une app ou un guide change sur le site, Adia le sait aussi.

import { Lang, translations } from '@/lib/i18n';
import { APPS, WHATSAPP_NUMBER } from '@/lib/apps.config';
import { PROJECT_META } from '@/lib/projects.config';
import { GUIDES } from '@/lib/guides';

export const SHOP_URL = 'https://fyxoqzyx.mychariow.shop/';
export const CONTACT_EMAIL = 'abidjandigital.ia@gmail.com';

const SERVICE_IDS = ['formations', 'infographies', 'sites-web'];

const L = {
  fr: {
    pages: 'Pages du site',
    agency: "L'agence",
    whyUs: 'Pourquoi nous choisir',
    services: 'Services',
    benefits: 'Bénéfices',
    deliverables: 'Exemples de livrables',
    requestLink: 'Demander ce service',
    about: 'À propos et fondateur',
    projects: 'Projets réalisés (page /projets)',
    apps: 'Applications (page /applications)',
    appOpen: 'Lien',
    appRestricted: "Accès restreint : pas de lien public, s'intéresser à l'app via WhatsApp",
    appSoon: "Pas encore disponible : on peut demander à être averti(e) du lancement via WhatsApp",
    appWarning:
      "Les applications en ligne sont réelles et déjà utilisées : on peut les visiter, mais il ne faut pas y créer de compte ni y saisir de fausses informations.",
    guides: 'Guides gratuits (page /guides, lecture libre, sans inscription)',
    outline: 'Sommaire',
    shop: 'Boutique (page /boutique)',
    contact: 'Contact',
    form: 'Formulaire de demande de service : page /contact',
    whatsapp: 'WhatsApp',
    email: 'Email',
    response: 'Délai de réponse',
    locations: 'Implantations',
    pageList: [
      '/ : accueil',
      '/services : les 3 services',
      '/projets : projets réalisés',
      '/applications : applications développées par l’agence',
      '/guides : guides gratuits',
      '/boutique : formations et ressources numériques à acheter',
      '/a-propos : l’agence et son fondateur',
      '/contact : formulaire de demande de service',
    ],
  },
  en: {
    pages: 'Site pages',
    agency: 'The agency',
    whyUs: 'Why choose us',
    services: 'Services',
    benefits: 'Benefits',
    deliverables: 'Example deliverables',
    requestLink: 'Request this service',
    about: 'About and founder',
    projects: 'Completed projects (page /projets)',
    apps: 'Applications (page /applications)',
    appOpen: 'Link',
    appRestricted: 'Restricted access: no public link, ask about the app on WhatsApp',
    appSoon: 'Not available yet: visitors can ask on WhatsApp to be notified at launch',
    appWarning:
      'Live apps are real and already in use: visitors can browse them but must not create accounts or enter fake information.',
    guides: 'Free guides (page /guides, free to read, no sign-up)',
    outline: 'Outline',
    shop: 'Shop (page /boutique)',
    contact: 'Contact',
    form: 'Service request form: page /contact',
    whatsapp: 'WhatsApp',
    email: 'Email',
    response: 'Response time',
    locations: 'Locations',
    pageList: [
      '/ : home',
      '/services : the 3 services',
      '/projets : completed projects',
      '/applications : apps built by the agency',
      '/guides : free guides',
      '/boutique : training and digital resources for sale',
      '/a-propos : the agency and its founder',
      '/contact : service request form',
    ],
  },
} as const;

const bullet = (s: string) => `- ${s}`;

export function buildAdiaKnowledge(lang: Lang): string {
  const t = translations[lang];
  const l = L[lang];
  const out: string[] = [];

  out.push(`## ${l.pages}`, ...l.pageList.map(bullet), '');

  out.push(
    `## ${l.agency}`,
    bullet(lang === 'fr' ? `Abidjan Digital.ia — « ${t.common.tagline} »` : `Abidjan Digital.ia — "${t.common.tagline}"`),
    bullet(t.footer.description),
    bullet(t.home.description),
    bullet(t.apropos.whoWeAreText),
    '',
    `### ${l.whyUs}`,
    ...t.home.reasons.map(r => bullet(`${r.title} : ${r.text}`)),
    ''
  );

  out.push(`## ${l.services}`);
  t.services.items.forEach((s, i) => {
    out.push(
      `### ${s.title}`,
      s.intro,
      `${l.benefits} : ${s.benefits.join(' ; ')}.`,
      `${l.deliverables} : ${s.deliverables.join(' ; ')}.`,
      `${l.requestLink} : /contact?service=${SERVICE_IDS[i]}`,
      ''
    );
  });

  out.push(
    `## ${l.about}`,
    ...t.apropos.values.map(v => bullet(`${v.title} : ${v.text}`)),
    bullet(`${t.apropos.founderName}, ${t.apropos.founderRole}.`),
    ...t.apropos.founderParagraphs.map(bullet),
    bullet(`${t.apropos.founderMissionPrefix}${t.apropos.founderMissionWords}`),
    bullet(`${t.apropos.usTitle} (${t.apropos.usCountry}) : ${t.apropos.usText}`),
    bullet(`${t.apropos.ciTitle} (${t.apropos.ciCountry}) : ${t.apropos.ciText}`),
    ''
  );

  out.push(`## ${l.projects}`);
  t.projets.items.forEach((p, i) => {
    const link = PROJECT_META[i]?.link;
    out.push(bullet(`${p.title} — ${p.description} (${p.tags.join(', ')})${link ? ` — ${l.appOpen} : ${link}` : ''}`));
  });
  out.push('');

  out.push(`## ${l.apps}`, l.appWarning);
  for (const app of APPS) {
    const tr = t.applications.apps[app.id];
    const status = t.applications.statusLabels[app.statut] ?? app.statut;
    const category = t.applications.categories[app.categorie] ?? app.categorie;
    let access: string;
    if (app.statut === 'bientot') access = l.appSoon;
    else if (app.accesDirect === false) access = l.appRestricted;
    else access = `${l.appOpen} : ${app.url}`;
    out.push(
      bullet(`${app.nom} [${category} · ${status}] — ${tr?.tagline ?? app.tagline} ${tr?.description ?? app.description} — ${access}`)
    );
  }
  out.push('');

  out.push(`## ${l.guides}`);
  for (const g of GUIDES) {
    const cat = t.guides.categories[g.category];
    const outline = g.sections.map(s => s.title[lang]).join(' ; ');
    out.push(bullet(`${g.title[lang]} [${cat}] — ${g.description[lang]} ${l.outline} : ${outline}. — /guides/${g.slug}`));
  }
  out.push('');

  out.push(`## ${l.shop}`, bullet(t.boutique.heroSubtitle), bullet(t.boutique.ctaText), bullet(SHOP_URL), '');

  out.push(
    `## ${l.contact}`,
    bullet(l.form),
    bullet(`${l.whatsapp} : https://wa.me/${WHATSAPP_NUMBER}`),
    bullet(`${l.email} : ${CONTACT_EMAIL}`),
    bullet(`${l.response} : ${t.contact.responseTimePrefix}${t.contact.responseTimeBold}${t.contact.responseTimeSuffix}`),
    bullet(`${l.locations} : ${t.footer.locationUS} ; ${t.footer.locationCI}`)
  );

  return out.join('\n');
}
