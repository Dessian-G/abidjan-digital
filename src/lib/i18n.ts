export type Lang = 'fr' | 'en';

export const LANG_STORAGE_KEY = 'adi-lang';

export const translations = {
  fr: {
    nav: {
      home: 'Accueil',
      services: 'Services',
      projects: 'Projets',
      applications: 'Applications',
      guides: 'Guides',
      boutique: 'Boutique',
      about: 'À propos',
      contact: 'Contact',
    },
    common: {
      requestService: 'Demander un service',
      tagline: 'Apprenez. Automatisez. Réussissez.',
      learnMore: 'En savoir plus →',
    },
    footer: {
      description: 'Studio web & intelligence artificielle au service des entrepreneurs francophones.',
      servicesHeading: 'Services',
      services: {
        formations: 'Formations digitales & IA',
        infographies: "Création d'infographies",
        sitesWeb: 'Création de sites web',
      },
      navHeading: 'Navigation',
      contactHeading: 'Contact',
      locationUS: 'Fort Worth, Texas — États-Unis',
      locationCI: "Abidjan (Cocody) — Côte d'Ivoire",
      rights: 'Tous droits réservés.',
    },
    home: {
      badge: '🌍 Fort Worth · Abidjan — Studio Web & IA',
      heroTitle: 'Votre allié digital & IA,',
      typewriter: ["d'Abidjan à Fort Worth", 'pour les entrepreneurs', "propulsé par l'IA"],
      description:
        "Formations digitales, infographies professionnelles et création de sites web — nous équipons les entrepreneurs francophones pour réussir à l'ère du numérique.",
      ctaServices: 'Voir nos services',
      servicesHeading: 'Nos services',
      servicesSubtitle: 'Trois expertises complémentaires pour accélérer votre développement digital.',
      services: [
        {
          title: 'Formations digitales & IA',
          description:
            "Apprenez à maîtriser les outils numériques et l'intelligence artificielle pour développer votre activité, même sans formation technique.",
        },
        {
          title: "Création d'infographies",
          description:
            'Des visuels professionnels et percutants pour vos réseaux sociaux et supports de communication qui reflètent votre marque.',
        },
        {
          title: 'Création de sites web',
          description:
            'Sites vitrines, e-commerce ou landing pages : nous concevons votre présence en ligne, de A à Z, rapide et moderne.',
        },
      ],
      reasonsHeading: 'Pourquoi nous choisir ?',
      reasonsSubtitle: "Un studio pensé pour les entrepreneurs francophones d'Afrique et de la diaspora.",
      reasons: [
        {
          title: 'Expertise IA & Digital',
          text: "Nous intégrons les derniers outils d'intelligence artificielle pour des résultats concrets et différenciants.",
        },
        {
          title: 'Double implantation',
          text: "Présents à Fort Worth (Texas) et à Abidjan (Cocody), nous comprenons les réalités de nos clients d'Afrique et de la diaspora.",
        },
        {
          title: 'Accompagnement personnalisé',
          text: 'Chaque projet est unique. Nous prenons le temps de comprendre votre besoin avant de proposer une solution adaptée.',
        },
        {
          title: 'Contenu de qualité',
          text: 'Que ce soit pour votre site, vos visuels ou vos formations, nous livrons un travail soigné et professionnel.',
        },
      ],
      projectsHeading: 'Nos projets',
      projectsSubtitle: 'Decouvrez nos realisations et les projets sur lesquels nous travaillons.',
      projects: [
        { title: 'Carnet Djassa', description: 'Carnet de dettes digital pour commerçants — reçus WhatsApp et relances en un clic.' },
        { title: 'Kaman Chantier', description: 'Suivi des dépenses de chantier pour la diaspora, preuve photo horodatée.' },
        { title: 'Ma Tontine', description: 'Gestion digitale de tontines : grille de cotisations et reçus automatiques.' },
      ],
      viewAllProjects: 'Voir tous les projets',
      ctaFinalTitle: 'Prêt à passer à l’action ?',
      ctaFinalText: 'Décrivez-nous votre projet en quelques lignes — nous vous répondons rapidement.',
      ctaFinalButton: 'Remplir le formulaire de demande',
    },
    services: {
      heroTitle: 'Nos services',
      heroSubtitle: 'Trois expertises pour accompagner votre développement digital, de la formation à la création.',
      benefitsHeading: 'Bénéfices',
      deliverablesHeading: 'Exemples de livrables',
      requestThis: 'Demander ce service',
      items: [
        {
          title: 'Formations digitales & IA',
          intro:
            "Nous vous accompagnons pas à pas pour maîtriser les outils numériques et l'intelligence artificielle, même sans bagage technique.",
          benefits: [
            'Formations adaptées à votre niveau et à votre secteur',
            'Outils IA concrets : ChatGPT, automatisation, création de contenu',
            'Prise en main rapide, résultats immédiats',
            'Suivi personnalisé après la formation',
          ],
          deliverables: ['Sessions individuelles ou en groupe', 'Supports pédagogiques fournis', 'Accès à des ressources exclusives'],
        },
        {
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
      ],
      ctaTitle: 'Un projet en tête ?',
      ctaText: 'Dites-nous ce dont vous avez besoin — nous vous répondrons sous 24h.',
      ctaButton: 'Remplir le formulaire de demande',
    },
    apropos: {
      heroTitle: 'À propos de nous',
      heroSubtitle: "Un studio digital & IA au service des entrepreneurs francophones, d'Abidjan à Fort Worth.",
      whoWeAreHeading: 'Qui sommes-nous ?',
      whoWeAreText:
        "Abidjan Digital.ia est un studio web & intelligence artificielle fondé avec une conviction forte : le numérique doit être un outil de croissance pour tous les entrepreneurs francophones, qu'ils soient en Afrique de l'Ouest ou dans la diaspora.",
      values: [
        {
          title: 'Notre mission',
          text: "Rendre le digital et l'intelligence artificielle accessibles aux entrepreneurs francophones et en faire un véritable levier de croissance.",
        },
        {
          title: 'Nos valeurs',
          text: 'Accessibilité, excellence et proximité. Nous croyons que chaque entrepreneur mérite un accompagnement de qualité, quelle que soit sa localisation.',
        },
        {
          title: 'Notre vision',
          text: "Un monde où les entrepreneurs d'Afrique et de la diaspora utilisent les meilleures technologies pour créer, innover et prospérer.",
        },
      ],
      founderHeading: 'Le fondateur',
      founderName: 'Max Guiro',
      founderRole: "Fondateur d'Abidjan Digital.ia",
      founderParagraphs: [
        "Max Guiro est le fondateur d'Abidjan Digital.ia. Entrepreneur passionné par le numérique et l'intelligence artificielle, il a créé l'agence avec une conviction simple : ces technologies doivent être accessibles aux entrepreneurs francophones et devenir un véritable levier de croissance.",
        "Depuis ses bureaux de Fort Worth (Texas) et d'Abidjan (Cocody), il accompagne porteurs de projets et entreprises dans leur transformation digitale — de la formation à la création de sites web, en passant par le design et l'automatisation.",
      ],
      founderMissionPrefix: 'Sa mission tient en trois mots : ',
      founderMissionWords: 'apprendre, automatiser, réussir.',
      presenceHeading: 'Notre présence internationale',
      presenceSubtitle: "Une double implantation stratégique pour mieux servir nos clients d'Afrique et de la diaspora.",
      usTitle: 'Fort Worth, Texas',
      usCountry: 'États-Unis',
      usText: 'Bureau principal pour les clients de la diaspora et du marché américain.',
      ciTitle: 'Abidjan, Cocody',
      ciCountry: "Côte d'Ivoire",
      ciText: "Présence locale pour accompagner les entrepreneurs d'Afrique de l'Ouest.",
      ctaTitle: 'Travaillons ensemble',
      ctaText: 'Vous avez un projet digital ? Décrivez-le nous, nous vous répondrons rapidement.',
      ctaButton: 'Nous contacter',
    },
    projets: {
      heroTitle: 'Nos projets',
      heroSubtitle: 'Decouvrez nos realisations : sites web, infographies et formations pour entrepreneurs.',
      categoryAll: 'Tous',
      categories: {
        siteWeb: 'Site web',
        infographie: 'Infographie',
        formation: 'Formation',
      },
      viewProject: 'Voir le projet',
      emptyTitle: 'Nos projets arrivent bientot',
      emptyText: 'Nous travaillons actuellement sur plusieurs projets passionnants. Revenez bientot pour decouvrir nos realisations !',
      emptyButton: 'Discutons de votre projet',
      items: [
        {
          title: 'Carnet Djassa',
          description:
            "Carnet de dettes digital pour commerçants d'Abidjan : suivi des crédits clients, reçus partageables et relances polies en un clic sur WhatsApp. Fonctionne même hors connexion.",
          tags: ['PWA', 'Firebase', 'Hors-ligne'],
        },
        {
          title: 'Mon Commerce',
          description:
            'Application de commande et de livraison avec espaces Admin, Livreur et Client — commande possible sans création de compte pour le client.',
          tags: ['React', 'Firebase', 'Livraison'],
        },
        {
          title: 'PharmaGarde CI',
          description:
            'Localisateur de pharmacies de garde à Abidjan en temps réel, pour trouver rapidement la pharmacie ouverte la plus proche.',
          tags: ['PWA', 'Firebase', 'Géolocalisation'],
        },
        {
          title: 'Kaman Chantier',
          description:
            "Suivi transparent des dépenses de chantier pour la diaspora : chaque dépense justifiée par une photo horodatée et non modifiable, tableau de bord temps réel pour le propriétaire.",
          tags: ['PWA', 'Firebase', 'Cloudinary'],
        },
        {
          title: 'Ma Tontine',
          description:
            'Gestion digitale de tontines : grille de cotisations membres × mois, reçus automatiques et rappels envoyés directement sur WhatsApp.',
          tags: ['PWA', 'Firebase', 'WhatsApp'],
        },
        {
          title: 'Formation en intelligence artificielle',
          description:
            "Formation pratique à l'IA pour entrepreneurs : ChatGPT et IA générative, automatisation avec n8n, création de contenu et outils d'IA pour le business.",
          tags: ['ChatGPT', 'n8n', 'IA générative'],
        },
        {
          title: 'Bohobli Skin Care',
          description:
            'Affiche publicitaire pour une gamme de soins de la peau : mise en scène des produits, identité visuelle et message de marque.',
          tags: ['Affiche', 'Cosmétique', 'Publicité'],
        },
        {
          title: 'Business IA Kit',
          description:
            "Visuel de couverture du Business IA Kit, « 50 prompts pour lancer ton business avec l'IA », pensé pour les réseaux sociaux.",
          tags: ['Couverture', 'Réseaux sociaux', 'IA'],
        },
      ],
      ctaTitle: 'Vous avez un projet ?',
      ctaText: 'Parlez-nous de votre idee — nous la transformons en realite digitale.',
      ctaButton: 'Demander un devis',
    },
    contact: {
      heroTitle: 'Demandez un service',
      heroSubtitle: 'Décrivez votre besoin en quelques lignes — nous vous répondons sous 24h.',
      formHeading: 'Formulaire de demande',
      detailsHeading: 'Nos coordonnées',
      emailLabel: 'Email',
      usLabel: '🇺🇸 États-Unis',
      usCity: 'Fort Worth, Texas',
      ciLabel: "🇨🇮 Côte d'Ivoire",
      ciCity: 'Abidjan (Cocody)',
      responseTimeHeading: 'Délai de réponse',
      responseTimePrefix: 'Nous répondons généralement dans les ',
      responseTimeBold: '24 heures',
      responseTimeSuffix: ' ouvrées.',
    },
    contactForm: {
      nameLabel: 'Nom complet',
      namePlaceholder: 'Marie Koné',
      emailLabel: 'Adresse email',
      emailPlaceholder: 'marie@exemple.com',
      phoneLabel: 'Téléphone / WhatsApp',
      phonePlaceholder: '+225 07 00 00 00 00',
      serviceLabel: 'Service souhaité',
      servicePlaceholder: 'Choisissez un service…',
      serviceFormation: 'Formation digitale & IA',
      serviceInfographie: "Création d'infographie",
      serviceSiteWeb: 'Création de site web',
      serviceAutre: 'Autre / Je ne sais pas encore',
      messageLabel: 'Description de votre besoin',
      messagePlaceholder: 'Décrivez votre projet, vos objectifs, vos délais… Plus vous êtes précis, mieux nous pourrons vous aider.',
      requiredPrefix: 'Les champs marqués ',
      requiredSuffix: ' sont obligatoires.',
      errorMessage: 'Une erreur est survenue. Veuillez réessayer ou écrire à abidjandigital.ia@gmail.com.',
      submitLoading: 'Envoi en cours…',
      submitIdle: 'Envoyer ma demande',
      successTitle: 'Demande envoyée !',
      successText: "Merci, votre demande a bien été envoyée. Nous vous répondrons rapidement à l'adresse indiquée.",
    },
    chatbot: {
      openLabel: 'Ouvrir le chat avec Adia',
      closeLabel: 'Fermer le chat',
      subtitle: 'Assistante Abidjan Digital.ia',
      welcome:
        "Bonjour 👋 Je suis Adia, l'assistante d'Abidjan Digital.ia. Je peux vous renseigner sur nos services (formations IA, infographies, sites web), nos applications, nos guides gratuits et notre boutique. Comment puis-je vous aider ?",
      typing: 'Adia écrit…',
      placeholder: 'Posez votre question…',
      sendLabel: 'Envoyer',
      errorMessage: 'Une erreur est survenue. Veuillez écrire à abidjandigital.ia@gmail.com.',
    },
    applications: {
      heroTitle: 'Nos applications',
      heroSubtitle:
        "Des outils numériques pensés pour les entrepreneurs africains — installables directement depuis votre navigateur, même sur une connexion instable. Une application vous intéresse ? Écrivez-nous en un clic sur WhatsApp.",
      filterAriaLabel: 'Filtrer par catégorie',
      categoryAll: 'Toutes',
      categories: {
        Commerce: 'Commerce',
        Finance: 'Finance',
        Agriculture: 'Agriculture',
        Éducation: 'Éducation',
        Communauté: 'Communauté',
        Outils: 'Outils',
      } as Record<string, string>,
      statusLabels: {
        'en-ligne': 'En ligne',
        beta: 'Bêta',
        bientot: 'Bientôt',
      } as Record<string, string>,
      openApp: "Ouvrir l'app",
      whatsappLabel: 'WhatsApp',
      notifyMe: 'Être averti(e)',
      whatsappAriaPrefix: 'Contacter sur WhatsApp au sujet de ',
      whatsappNotifyMessagePrefix: "Bonjour ! Je souhaite être averti(e) du lancement de l'application ",
      whatsappInterestMessagePrefix: "Bonjour ! Je suis intéressé(e) par l'application ",
      whatsappInterestMessageSuffix: " et j'aimerais en savoir plus.",
      customProjectLabel: 'un projet sur mesure',
      emptyCategory: 'Aucune application dans cette catégorie pour le moment.',
      ctaTitle: "Une idée d'application pour votre activité ?",
      ctaText: 'Parlez-nous de votre besoin — nous concevons des outils numériques sur mesure.',
      ctaButton: 'Discuter sur WhatsApp',
      modal: {
        title: 'Vous quittez le portfolio',
        closeLabel: 'Fermer',
        warningSuffix:
          ' est une application réelle, déjà utilisée par de vrais utilisateurs. Vous pouvez la visiter librement, mais merci de ne pas y créer de compte ni y saisir de fausses informations.',
        cancel: 'Annuler',
        confirm: 'Continuer',
      },
      apps: {
        'carnet-djassa': {
          tagline: 'Le carnet de crédit de ta boutique, dans ta poche.',
          description:
            'Remplace le cahier manuel : enregistre les crédits clients, reçois des rappels et suis tes encaissements en temps réel.',
        },
        'commerce-ivoire': {
          tagline: "Tous les commerces d'Abidjan, triés par proximité.",
          description:
            "Annuaire des commerces du District d'Abidjan avec recherche par quartier et mise en relation pour la livraison.",
        },
        'mon-commerce': {
          tagline: 'Commande et livraison, simple pour toi et tes clients.',
          description:
            'Application de commande et de livraison avec espaces Admin, Livreur et Client — commande possible sans création de compte pour le client.',
        },
        agriconnect: {
          tagline: 'Le marché, la météo et les conseils IA pour les planteurs.',
          description:
            "Prix du marché par région, alertes météo et conseils agricoles assistés par IA, pensés pour la Côte d'Ivoire.",
        },
        'gestion-associations': {
          tagline: 'Gère ton association sans tableur ni papier.',
          description:
            'Membres, cotisations, événements et notifications WhatsApp — la PWA de gestion pour associations et mutuelles.',
        },
        'kaman-chantier': {
          tagline: "Suis les dépenses de ton chantier depuis l'étranger.",
          description:
            'Pour la diaspora qui construit au pays : chaque dépense justifiée par photo, chaque franc tracé.',
        },
        'revision-hors-ligne': {
          tagline: 'Fiches et quiz qui marchent même sans connexion.',
          description:
            'PWA de révision hors ligne pour les élèves : fiches, quiz et suivi de progression stockés sur le téléphone.',
        },
        'ma-tontine': {
          tagline: 'Organisez et suivez les tontines de votre groupe, sans tableur.',
          description:
            'Gestion des cotisations, des tours de tontine et des membres, avec suivi des paiements en temps réel.',
        },
        'royal-ministry': {
          tagline: 'Gérez les membres et activités de votre organisation religieuse, simplement.',
          description:
            'Suivi des membres, des dons/offrandes et des événements pour paroisses, ministères et organisations religieuses.',
        },
        'abidjan-circul': {
          tagline: "L'état du trafic à Abidjan, pont par pont, en temps réel.",
          description:
            "Circulation en direct dans les 13 communes et sur les 4 ponts du District d'Abidjan, avec signalements communautaires et conseil de départ.",
        },
      } as Record<string, { tagline: string; description: string }>,
    },
    boutique: {
      heroTitle: 'Notre boutique',
      heroSubtitle:
        "Formations, ressources et produits numériques d'Abidjan Digital.ia, disponibles directement en ligne.",
      ctaTitle: 'Découvrez nos offres',
      ctaText:
        "Notre boutique en ligne rassemble nos formations et ressources numériques, prêtes à l'achat en quelques clics.",
      ctaButton: 'Visiter la boutique',
    },
    guides: {
      metaTitle: 'Guides gratuits — Abidjan Digital.ia',
      eyebrow: 'Guides gratuits',
      heroTitle: 'Nos guides gratuits : IA, images, vidéo, WhatsApp et web',
      heroLead:
        "Des guides concrets, pensés pour les entrepreneurs d'Abidjan et de la diaspora. Chaque guide contient des étapes claires et des prompts prêts à copier, pour passer à l'action tout de suite.",
      browseAll: 'Voir tous les guides',
      subscribe: 'Recevoir les prochains guides',
      statGuides: 'guides gratuits',
      statFree: 'Lecture libre, sans inscription',
      statSearch: 'pour chercher',
      statSearchPrefix: 'Appuyez sur',
      startHere: 'Commencez ici',
      libraryTitle: "Trouvez le guide qu'il vous faut",
      librarySubtitle: 'Filtrez par thème, ou cherchez un outil comme ChatGPT, Canva, CapCut ou WhatsApp.',
      searchLabel: 'Rechercher un guide',
      searchPlaceholder: 'Rechercher un guide…',
      all: 'Tous',
      showingOne: 'guide affiché',
      showingMany: 'guides affichés',
      emptyTitle: 'Aucun guide ne correspond pour le moment.',
      emptyText: 'Essayez un outil comme ChatGPT, Canva ou WhatsApp, ou un thème comme « visuels » ou « site web ».',
      clearSearch: 'Effacer la recherche',
      readGuide: 'Lire le guide',
      minRead: 'min de lecture',
      pinned: 'Essentiel',
      featured: 'À la une',
      updated: 'Mis à jour le',
      categories: {
        ia: 'IA & prompts',
        reseaux: 'Réseaux sociaux & visuels',
        vente: 'WhatsApp & vente',
        web: 'Web & visibilité',
        images: 'Images IA',
        video: 'Vidéo & voix',
        carriere: 'Emploi & carrière',
      },
      newsletterTitle: 'Recevez le prochain guide',
      newsletterText: "Laissez votre email : nous vous prévenons dès qu'un nouveau guide gratuit est publié.",
      newsletterItemsHeading: 'Dans chaque guide',
      newsletterItems: ['Un sujet pratique et concret', 'Les étapes et les prompts exacts', "Des outils adaptés à l'Afrique et à la diaspora"],
      emailLabel: 'Adresse email',
      emailPlaceholder: 'vous@exemple.com',
      join: "Je m'inscris",
      sending: 'Envoi…',
      microcopy: 'Gratuit. Pas de spam : uniquement les nouveaux guides. Pour vous désinscrire, il suffit de nous écrire.',
      success: 'Merci ! Vous serez prévenu(e) à la sortie du prochain guide.',
      error: 'Une erreur est survenue. Réessayez ou écrivez-nous sur WhatsApp.',
      orWhatsapp: 'Vous préférez WhatsApp ?',
      whatsappCta: 'Recevoir les guides sur WhatsApp',
      whatsappMessage: "Bonjour ! J'aimerais recevoir les nouveaux guides gratuits d'Abidjan Digital.ia sur WhatsApp.",
      backToGuides: 'Tous les guides',
      promptLabel: 'Prompt à copier',
      copy: 'Copier',
      copied: 'Copié !',
      tipLabel: 'Astuce :',
      ctaTitle: "Envie d'aller plus loin ?",
      ctaText: 'Abidjan Digital.ia vous forme et vous accompagne : formations IA, création de visuels et de sites web.',
      ctaButton: 'Demander un service',
      ctaWhatsapp: 'Poser une question sur WhatsApp',
      whatsappQuestionPrefix: "Bonjour ! J'ai lu votre guide « ",
      whatsappQuestionSuffix: ' » et j\'ai une question.',
      moreGuides: 'Autres guides',
    },
  },
  en: {
    nav: {
      home: 'Home',
      services: 'Services',
      projects: 'Projects',
      applications: 'Applications',
      guides: 'Guides',
      boutique: 'Shop',
      about: 'About',
      contact: 'Contact',
    },
    common: {
      requestService: 'Request a service',
      tagline: 'Learn. Automate. Succeed.',
      learnMore: 'Learn more →',
    },
    footer: {
      description: 'Web & artificial intelligence studio serving French-speaking entrepreneurs.',
      servicesHeading: 'Services',
      services: {
        formations: 'Digital & AI training',
        infographies: 'Graphic design',
        sitesWeb: 'Website creation',
      },
      navHeading: 'Navigation',
      contactHeading: 'Contact',
      locationUS: 'Fort Worth, Texas — United States',
      locationCI: "Abidjan (Cocody) — Côte d'Ivoire",
      rights: 'All rights reserved.',
    },
    home: {
      badge: '🌍 Fort Worth · Abidjan — Web & AI Studio',
      heroTitle: 'Your digital & AI ally,',
      typewriter: ['from Abidjan to Fort Worth', 'for entrepreneurs', 'powered by AI'],
      description:
        'Digital training, professional graphic design and website creation — we equip French-speaking entrepreneurs to succeed in the digital age.',
      ctaServices: 'See our services',
      servicesHeading: 'Our services',
      servicesSubtitle: 'Three complementary areas of expertise to accelerate your digital growth.',
      services: [
        {
          title: 'Digital & AI training',
          description:
            'Learn to master digital tools and artificial intelligence to grow your business, even without a technical background.',
        },
        {
          title: 'Graphic design',
          description:
            'Professional, impactful visuals for your social media and communication materials that reflect your brand.',
        },
        {
          title: 'Website creation',
          description:
            'Showcase sites, e-commerce or landing pages: we design your online presence from A to Z, fast and modern.',
        },
      ],
      reasonsHeading: 'Why choose us?',
      reasonsSubtitle: 'A studio built for French-speaking entrepreneurs across Africa and the diaspora.',
      reasons: [
        {
          title: 'AI & Digital expertise',
          text: 'We integrate the latest artificial intelligence tools for concrete, differentiating results.',
        },
        {
          title: 'Dual presence',
          text: 'Based in Fort Worth (Texas) and Abidjan (Cocody), we understand the realities of our clients across Africa and the diaspora.',
        },
        {
          title: 'Personalized support',
          text: 'Every project is unique. We take the time to understand your needs before proposing a tailored solution.',
        },
        {
          title: 'Quality content',
          text: 'Whether for your website, your visuals or your training, we deliver polished, professional work.',
        },
      ],
      projectsHeading: 'Our projects',
      projectsSubtitle: 'Discover what we’ve built and the projects we’re currently working on.',
      projects: [
        { title: 'Carnet Djassa', description: 'Digital debt ledger for shop owners — WhatsApp receipts and one-click reminders.' },
        { title: 'Kaman Chantier', description: 'Construction site expense tracking for the diaspora, with timestamped photo proof.' },
        { title: 'Ma Tontine', description: 'Digital tontine management: contribution grid and automatic receipts.' },
      ],
      viewAllProjects: 'View all projects',
      ctaFinalTitle: 'Ready to take action?',
      ctaFinalText: 'Tell us about your project in a few lines — we’ll get back to you quickly.',
      ctaFinalButton: 'Fill out the request form',
    },
    services: {
      heroTitle: 'Our services',
      heroSubtitle: 'Three areas of expertise to support your digital growth, from training to creation.',
      benefitsHeading: 'Benefits',
      deliverablesHeading: 'Example deliverables',
      requestThis: 'Request this service',
      items: [
        {
          title: 'Digital & AI training',
          intro:
            'We guide you step by step to master digital tools and artificial intelligence, even without a technical background.',
          benefits: [
            'Training tailored to your level and industry',
            'Practical AI tools: ChatGPT, automation, content creation',
            'Quick to pick up, immediate results',
            'Personalized follow-up after training',
          ],
          deliverables: ['Individual or group sessions', 'Learning materials provided', 'Access to exclusive resources'],
        },
        {
          title: 'Graphic design',
          intro:
            'Professional, impactful visuals tailored to your brand identity — for your social media, presentations and communication materials.',
          benefits: [
            'Modern design consistent with your brand image',
            'Formats adapted to each platform (Instagram, LinkedIn, Facebook…)',
            'Fast delivery in high definition',
            'Revisions included',
          ],
          deliverables: ['Social media graphics', 'Visual presentations', 'Banners and posters', 'Carousels and stories'],
        },
        {
          title: 'Website creation',
          intro:
            'Your tailor-made digital storefront: professional site, e-commerce or landing page — fast, modern and optimized to convert visitors into customers.',
          benefits: [
            'Custom design, tailored to your industry',
            '100% responsive (mobile, tablet, desktop)',
            'Search engine optimized (SEO)',
            'Contact form and tool integration (CRM, email…)',
          ],
          deliverables: ['Professional showcase site', 'Online store (e-commerce)', 'Conversion landing page', 'Redesign of an existing site'],
        },
      ],
      ctaTitle: 'Have a project in mind?',
      ctaText: "Tell us what you need — we'll respond within 24 hours.",
      ctaButton: 'Fill out the request form',
    },
    apropos: {
      heroTitle: 'About us',
      heroSubtitle: 'A digital & AI studio serving French-speaking entrepreneurs, from Abidjan to Fort Worth.',
      whoWeAreHeading: 'Who we are',
      whoWeAreText:
        "Abidjan Digital.ia is a web & artificial intelligence studio founded on a strong conviction: digital technology should be a growth tool for every French-speaking entrepreneur, whether in West Africa or the diaspora.",
      values: [
        {
          title: 'Our mission',
          text: 'Make digital technology and artificial intelligence accessible to French-speaking entrepreneurs and turn them into a real growth lever.',
        },
        {
          title: 'Our values',
          text: 'Accessibility, excellence and closeness. We believe every entrepreneur deserves quality support, regardless of location.',
        },
        {
          title: 'Our vision',
          text: 'A world where entrepreneurs from Africa and the diaspora use the best technologies to create, innovate and thrive.',
        },
      ],
      founderHeading: 'The founder',
      founderName: 'Max Guiro',
      founderRole: 'Founder of Abidjan Digital.ia',
      founderParagraphs: [
        'Max Guiro is the founder of Abidjan Digital.ia. An entrepreneur passionate about digital technology and artificial intelligence, he created the agency on a simple conviction: these technologies should be accessible to French-speaking entrepreneurs and become a real growth lever.',
        'From his offices in Fort Worth (Texas) and Abidjan (Cocody), he supports project owners and businesses through their digital transformation — from training to website creation, including design and automation.',
      ],
      founderMissionPrefix: 'His mission comes down to three words: ',
      founderMissionWords: 'learn, automate, succeed.',
      presenceHeading: 'Our international presence',
      presenceSubtitle: 'A strategic dual presence to better serve our clients across Africa and the diaspora.',
      usTitle: 'Fort Worth, Texas',
      usCountry: 'United States',
      usText: 'Main office for diaspora clients and the U.S. market.',
      ciTitle: 'Abidjan, Cocody',
      ciCountry: "Côte d'Ivoire",
      ciText: 'Local presence to support entrepreneurs across West Africa.',
      ctaTitle: "Let's work together",
      ctaText: 'Have a digital project? Tell us about it, we’ll get back to you quickly.',
      ctaButton: 'Contact us',
    },
    projets: {
      heroTitle: 'Our projects',
      heroSubtitle: 'Discover what we’ve built: websites, graphic design and training for entrepreneurs.',
      categoryAll: 'All',
      categories: {
        siteWeb: 'Website',
        infographie: 'Graphic design',
        formation: 'Training',
      },
      viewProject: 'View project',
      emptyTitle: 'Our projects are coming soon',
      emptyText: "We're currently working on several exciting projects. Check back soon to see what we've built!",
      emptyButton: "Let's talk about your project",
      items: [
        {
          title: 'Carnet Djassa',
          description:
            "Digital debt ledger for shop owners in Abidjan: track customer credit, shareable receipts and polite one-click reminders on WhatsApp. Works even offline.",
          tags: ['PWA', 'Firebase', 'Offline-first'],
        },
        {
          title: 'Mon Commerce',
          description:
            'Ordering and delivery app with Admin, Courier and Customer spaces — customers can order without creating an account.',
          tags: ['React', 'Firebase', 'Delivery'],
        },
        {
          title: 'PharmaGarde CI',
          description:
            'Real-time locator for on-duty pharmacies in Abidjan, to quickly find the nearest open pharmacy.',
          tags: ['PWA', 'Firebase', 'Geolocation'],
        },
        {
          title: 'Kaman Chantier',
          description:
            'Transparent construction site expense tracking for the diaspora: every expense backed by a timestamped, tamper-proof photo, with a real-time dashboard for the owner.',
          tags: ['PWA', 'Firebase', 'Cloudinary'],
        },
        {
          title: 'Ma Tontine',
          description:
            'Digital tontine management: members × months contribution grid, automatic receipts and reminders sent directly on WhatsApp.',
          tags: ['PWA', 'Firebase', 'WhatsApp'],
        },
        {
          title: 'Artificial intelligence training',
          description:
            'Hands-on AI training for entrepreneurs: ChatGPT and generative AI, automation with n8n, content creation and AI tools for business.',
          tags: ['ChatGPT', 'n8n', 'Generative AI'],
        },
        {
          title: 'Bohobli Skin Care',
          description:
            'Advertising poster for a skincare range: product staging, visual identity and brand message.',
          tags: ['Poster', 'Cosmetics', 'Advertising'],
        },
        {
          title: 'Business IA Kit',
          description:
            'Cover visual for the Business IA Kit, "50 prompts to launch your business with AI", designed for social media.',
          tags: ['Cover', 'Social media', 'AI'],
        },
      ],
      ctaTitle: 'Have a project?',
      ctaText: "Tell us about your idea — we'll turn it into digital reality.",
      ctaButton: 'Request a quote',
    },
    contact: {
      heroTitle: 'Request a service',
      heroSubtitle: "Describe your need in a few lines — we'll respond within 24 hours.",
      formHeading: 'Request form',
      detailsHeading: 'Our contact details',
      emailLabel: 'Email',
      usLabel: '🇺🇸 United States',
      usCity: 'Fort Worth, Texas',
      ciLabel: "🇨🇮 Côte d'Ivoire",
      ciCity: 'Abidjan (Cocody)',
      responseTimeHeading: 'Response time',
      responseTimePrefix: 'We typically respond within ',
      responseTimeBold: '24 hours',
      responseTimeSuffix: ' on business days.',
    },
    contactForm: {
      nameLabel: 'Full name',
      namePlaceholder: 'Marie Koné',
      emailLabel: 'Email address',
      emailPlaceholder: 'marie@example.com',
      phoneLabel: 'Phone / WhatsApp',
      phonePlaceholder: '+225 07 00 00 00 00',
      serviceLabel: 'Service needed',
      servicePlaceholder: 'Choose a service…',
      serviceFormation: 'Digital & AI training',
      serviceInfographie: 'Graphic design',
      serviceSiteWeb: 'Website creation',
      serviceAutre: "Other / I'm not sure yet",
      messageLabel: 'Describe your need',
      messagePlaceholder: "Describe your project, your goals, your timeline… The more specific you are, the better we can help.",
      requiredPrefix: 'Fields marked ',
      requiredSuffix: ' are required.',
      errorMessage: 'Something went wrong. Please try again or email abidjandigital.ia@gmail.com.',
      submitLoading: 'Sending…',
      submitIdle: 'Send my request',
      successTitle: 'Request sent!',
      successText: "Thank you, your request has been sent. We'll get back to you shortly at the address you provided.",
    },
    chatbot: {
      openLabel: 'Open chat with Adia',
      closeLabel: 'Close chat',
      subtitle: 'Abidjan Digital.ia Assistant',
      welcome:
        "Hello 👋 I'm Adia, the Abidjan Digital.ia assistant. I can tell you about our services (AI training, graphic design, websites), our apps, our free guides and our shop. How can I help you?",
      typing: 'Adia is typing…',
      placeholder: 'Ask your question…',
      sendLabel: 'Send',
      errorMessage: 'Something went wrong. Please email abidjandigital.ia@gmail.com.',
    },
    applications: {
      heroTitle: 'Our applications',
      heroSubtitle:
        "Digital tools designed for African entrepreneurs — installable straight from your browser, even on an unstable connection. Interested in an app? Message us on WhatsApp in one click.",
      filterAriaLabel: 'Filter by category',
      categoryAll: 'All',
      categories: {
        Commerce: 'Commerce',
        Finance: 'Finance',
        Agriculture: 'Agriculture',
        Éducation: 'Education',
        Communauté: 'Community',
        Outils: 'Tools',
      } as Record<string, string>,
      statusLabels: {
        'en-ligne': 'Live',
        beta: 'Beta',
        bientot: 'Coming soon',
      } as Record<string, string>,
      openApp: 'Open app',
      whatsappLabel: 'WhatsApp',
      notifyMe: 'Notify me',
      whatsappAriaPrefix: 'Contact on WhatsApp about ',
      whatsappNotifyMessagePrefix: "Hello! I'd like to be notified when the app ",
      whatsappInterestMessagePrefix: "Hello! I'm interested in the app ",
      whatsappInterestMessageSuffix: " and I'd like to know more.",
      customProjectLabel: 'a custom project',
      emptyCategory: 'No applications in this category yet.',
      ctaTitle: 'Have an app idea for your business?',
      ctaText: "Tell us what you need — we design custom digital tools.",
      ctaButton: 'Chat on WhatsApp',
      modal: {
        title: 'You are leaving the portfolio',
        closeLabel: 'Close',
        warningSuffix:
          ' is a real application, already used by real users. Feel free to browse it, but please avoid creating an account or entering fake information.',
        cancel: 'Cancel',
        confirm: 'Continue',
      },
      apps: {
        'carnet-djassa': {
          tagline: "Your shop's credit ledger, right in your pocket.",
          description:
            'Replaces the paper notebook: log customer credit, get reminders, and track your collections in real time.',
        },
        'commerce-ivoire': {
          tagline: "Every business in Abidjan, sorted by proximity.",
          description:
            'Directory of businesses across the Abidjan District with neighborhood search and delivery matching.',
        },
        'mon-commerce': {
          tagline: 'Ordering and delivery, simple for you and your customers.',
          description:
            'Ordering and delivery app with Admin, Courier and Customer spaces — customers can order without creating an account.',
        },
        agriconnect: {
          tagline: 'Market prices, weather and AI advice for growers.',
          description:
            "Regional market prices, weather alerts and AI-assisted farming advice, built for Côte d'Ivoire.",
        },
        'gestion-associations': {
          tagline: 'Run your association without a spreadsheet or paper.',
          description:
            'Members, dues, events and WhatsApp notifications — the management PWA for associations and mutual aid groups.',
        },
        'kaman-chantier': {
          tagline: 'Track your construction site expenses from abroad.',
          description:
            'For the diaspora building back home: every expense backed by a photo, every franc accounted for.',
        },
        'revision-hors-ligne': {
          tagline: 'Flashcards and quizzes that work even offline.',
          description:
            'Offline study PWA for students: flashcards, quizzes and progress tracking stored on the phone.',
        },
        'ma-tontine': {
          tagline: "Organize and track your group's savings circle, no spreadsheet needed.",
          description:
            'Manage contributions, payout rounds and members, with real-time payment tracking.',
        },
        'royal-ministry': {
          tagline: 'Manage your religious organization\'s members and activities, simply.',
          description:
            'Track members, donations/offerings and events for parishes, ministries and religious organizations.',
        },
        'abidjan-circul': {
          tagline: "Abidjan traffic conditions, bridge by bridge, in real time.",
          description:
            "Live traffic across the Abidjan District's 13 communes and 4 bridges, with community reports and departure advice.",
        },
      } as Record<string, { tagline: string; description: string }>,
    },
    boutique: {
      heroTitle: 'Our shop',
      heroSubtitle:
        "Abidjan Digital.ia's training, resources and digital products, available directly online.",
      ctaTitle: 'Discover our offers',
      ctaText:
        'Our online shop brings together our training and digital resources, ready to purchase in just a few clicks.',
      ctaButton: 'Visit the shop',
    },
    guides: {
      metaTitle: 'Free guides — Abidjan Digital.ia',
      eyebrow: 'Free guides',
      heroTitle: 'Our free guides: AI, images, video, WhatsApp and web',
      heroLead:
        'Practical guides built for entrepreneurs in Abidjan and across the diaspora. Every guide has clear steps and copy-ready prompts, so you can take action right away.',
      browseAll: 'Browse all guides',
      subscribe: 'Get the next guides',
      statGuides: 'free guides',
      statFree: 'Free to read, no sign-up',
      statSearch: 'to search',
      statSearchPrefix: 'Press',
      startHere: 'Start here',
      libraryTitle: 'Find the guide you need',
      librarySubtitle: 'Filter by topic, or search for a tool like ChatGPT, Canva, CapCut or WhatsApp.',
      searchLabel: 'Search the guides',
      searchPlaceholder: 'Search the guides…',
      all: 'All',
      showingOne: 'guide shown',
      showingMany: 'guides shown',
      emptyTitle: 'No guides match that yet.',
      emptyText: 'Try a tool like ChatGPT, Canva or WhatsApp, or a topic like "visuals" or "website".',
      clearSearch: 'Clear search',
      readGuide: 'Read guide',
      minRead: 'min read',
      pinned: 'Essential',
      featured: 'Featured',
      updated: 'Updated',
      categories: {
        ia: 'AI & prompts',
        reseaux: 'Social media & visuals',
        vente: 'WhatsApp & sales',
        web: 'Web & visibility',
        images: 'AI images',
        video: 'Video & voice',
        carriere: 'Jobs & career',
      },
      newsletterTitle: 'Get the next guide',
      newsletterText: 'Leave your email and we will let you know as soon as a new free guide is published.',
      newsletterItemsHeading: 'In every guide',
      newsletterItems: ['One practical, concrete topic', 'The exact steps and prompts', 'Tools suited to Africa and the diaspora'],
      emailLabel: 'Email address',
      emailPlaceholder: 'you@example.com',
      join: 'Sign me up',
      sending: 'Sending…',
      microcopy: 'Free. No spam: only new guides. To unsubscribe, just write to us.',
      success: 'Thank you! We will let you know when the next guide is out.',
      error: 'Something went wrong. Please try again or message us on WhatsApp.',
      orWhatsapp: 'Prefer WhatsApp?',
      whatsappCta: 'Get the guides on WhatsApp',
      whatsappMessage: "Hello! I'd like to receive Abidjan Digital.ia's new free guides on WhatsApp.",
      backToGuides: 'All guides',
      promptLabel: 'Prompt to copy',
      copy: 'Copy',
      copied: 'Copied!',
      tipLabel: 'Tip:',
      ctaTitle: 'Want to go further?',
      ctaText: 'Abidjan Digital.ia trains and supports you: AI training, graphic design and website creation.',
      ctaButton: 'Request a service',
      ctaWhatsapp: 'Ask a question on WhatsApp',
      whatsappQuestionPrefix: 'Hello! I read your guide "',
      whatsappQuestionSuffix: '" and I have a question.',
      moreGuides: 'More guides',
    },
  },
} satisfies Record<Lang, Record<string, unknown>>;

export type Translations = typeof translations.fr;
