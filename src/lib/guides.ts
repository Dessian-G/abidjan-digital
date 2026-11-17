// src/lib/guides.ts
// Bibliothèque des guides gratuits d'Abidjan Digital.ia (page /guides).
// Pour ajouter un guide : ajoute un objet dans GUIDES, rien d'autre à toucher.
// Le temps de lecture est calculé automatiquement à partir du texte.

import type { Lang } from '@/lib/i18n';

export type GuideCategory = 'ia' | 'images' | 'video' | 'reseaux' | 'vente' | 'web' | 'carriere';

export const GUIDE_CATEGORIES: GuideCategory[] = ['ia', 'images', 'video', 'reseaux', 'vente', 'web', 'carriere'];

export const GUIDE_CATEGORY_COLORS: Record<GuideCategory, string> = {
  ia: '#0E2A63',
  reseaux: '#F4791F',
  vente: '#4FB52B',
  web: '#0891B2',
  images: '#DB2777',
  video: '#7C3AED',
  carriere: '#B45309',
};

type Text = Record<Lang, string>;
type TextList = Record<Lang, string[]>;

export interface GuideSection {
  title: Text;
  text?: Text;
  bullets?: TextList;
  ordered?: boolean; // true = liste numérotée (étapes)
  prompt?: Text; // prompt prêt à copier
  prompts?: Text[]; // plusieurs prompts dans la même section
  tip?: Text;
}

export interface Guide {
  slug: string;
  category: GuideCategory;
  tools: string[]; // outils cités, affichés sur la carte et utilisés par la recherche
  updated: string; // AAAA-MM-JJ
  pinned?: boolean; // affiché dans « Commencez ici »
  featured?: boolean; // mis en avant en tête de la bibliothèque
  title: Text;
  description: Text;
  intro: Text;
  sections: GuideSection[];
}

export const GUIDES: Guide[] = [
  {
    slug: 'demarrer-avec-l-ia',
    category: 'ia',
    tools: ['ChatGPT', 'Claude', 'Gemini'],
    updated: '2026-11-17',
    pinned: true,
    title: {
      fr: "Le guide complet pour démarrer avec l'IA",
      en: 'The complete guide to getting started with AI',
    },
    description: {
      fr: "ChatGPT, Claude ou Gemini : lequel choisir, comment bien leur parler et vos 3 premiers usages pour gagner du temps dès aujourd'hui.",
      en: 'ChatGPT, Claude or Gemini: which one to pick, how to talk to them, and your first 3 uses to save time starting today.',
    },
    intro: {
      fr: "Pas besoin d'être informaticien pour utiliser l'intelligence artificielle. Ce guide vous montre, étape par étape, comment vous en servir pour votre activité : écrire, répondre à vos clients, vous organiser et réfléchir plus vite.",
      en: "You don't need to be a tech expert to use artificial intelligence. This guide shows you, step by step, how to use it for your business: writing, answering customers, getting organised and thinking faster.",
    },
    sections: [
      {
        title: { fr: 'Choisir son assistant IA', en: 'Choose your AI assistant' },
        text: {
          fr: 'Les trois assistants les plus connus sont ChatGPT (OpenAI), Claude (Anthropic) et Gemini (Google). Tous ont une version gratuite qui suffit pour commencer, sur ordinateur comme en application mobile.',
          en: 'The three best-known assistants are ChatGPT (OpenAI), Claude (Anthropic) and Gemini (Google). All of them have a free version that is enough to get started, on a computer or as a mobile app.',
        },
        bullets: {
          fr: [
            'ChatGPT : le plus connu, très polyvalent.',
            'Claude : excellent pour écrire des textes longs et naturels, en français comme en anglais.',
            'Gemini : pratique si vous utilisez déjà Gmail et Google Drive.',
          ],
          en: [
            'ChatGPT: the best known, very versatile.',
            'Claude: excellent for writing long, natural-sounding texts, in English as well as French.',
            'Gemini: handy if you already use Gmail and Google Drive.',
          ],
        },
        tip: {
          fr: "Choisissez-en un seul et utilisez-le tous les jours pendant une semaine. C'est la pratique qui fait la différence, pas l'outil.",
          en: "Pick just one and use it every day for a week. Practice makes the difference, not the tool.",
        },
      },
      {
        title: { fr: 'La méthode R-C-T-F pour bien demander', en: 'The R-C-T-F method for better requests' },
        text: {
          fr: 'Une bonne réponse commence par une bonne demande (un « prompt »). Donnez toujours ces 4 éléments :',
          en: 'A good answer starts with a good request (a "prompt"). Always give these 4 elements:',
        },
        bullets: {
          fr: [
            "Rôle : qui l'IA doit être (« Tu es un expert en marketing »).",
            'Contexte : votre activité, vos clients, votre ville.',
            'Tâche : ce que vous voulez exactement.',
            'Format : la forme de la réponse (liste, message WhatsApp, tableau, 5 lignes maximum).',
          ],
          en: [
            'Role: who the AI should be ("You are a marketing expert").',
            'Context: your business, your customers, your city.',
            'Task: exactly what you want.',
            'Format: the shape of the answer (list, WhatsApp message, table, 5 lines max).',
          ],
        },
        prompt: {
          fr: "Tu es un expert en marketing pour les petits commerces à Abidjan. Je vends [votre produit] à [votre clientèle] dans le quartier de [quartier]. Propose-moi 5 idées de promotions pour le mois prochain. Présente-les en liste, avec pour chacune une phrase d'accroche.",
          en: 'You are a marketing expert for small businesses in [your city]. I sell [your product] to [your customers] in [neighbourhood]. Suggest 5 promotion ideas for next month. Present them as a list, each with a catchy one-liner.',
        },
      },
      {
        title: { fr: 'Vos 3 premiers usages', en: 'Your first 3 uses' },
        bullets: {
          fr: [
            'Écrire : publications, descriptions de produits, réponses aux clients.',
            'Résumer : un long document, un compte rendu, un contrat (en gardant un œil critique).',
            'Réfléchir : préparer une négociation, trouver un nom de marque, organiser votre semaine.',
          ],
          en: [
            'Write: social posts, product descriptions, replies to customers.',
            'Summarise: a long document, meeting notes, a contract (while staying critical).',
            'Think: prepare a negotiation, find a brand name, plan your week.',
          ],
        },
        prompt: {
          fr: "Voici un message reçu d'un client : [collez le message]. Rédige une réponse polie et professionnelle, en 4 phrases maximum, qui [rassure le client / propose une solution / annonce un délai].",
          en: "Here is a message from a customer: [paste the message]. Write a polite, professional reply in 4 sentences max that [reassures the customer / offers a solution / gives a timeline].",
        },
      },
      {
        title: { fr: 'Améliorer une réponse au lieu de recommencer', en: 'Improve an answer instead of starting over' },
        text: {
          fr: 'La première réponse est rarement parfaite. Continuez simplement la conversation :',
          en: 'The first answer is rarely perfect. Simply keep the conversation going:',
        },
        bullets: {
          fr: [
            '« Plus court, s\'il te plaît. »',
            '« Plus chaleureux, comme si tu parlais à un client fidèle. »',
            '« Ajoute un appel à l\'action à la fin. »',
            '« Donne-moi 3 autres versions. »',
          ],
          en: [
            '"Shorter, please."',
            '"Warmer, as if you were talking to a loyal customer."',
            '"Add a call to action at the end."',
            '"Give me 3 more versions."',
          ],
        },
      },
      {
        title: { fr: 'Les règles de prudence', en: 'Safety rules' },
        bullets: {
          fr: [
            "Vérifiez toujours les chiffres, les dates et les informations légales : l'IA peut se tromper avec beaucoup d'assurance.",
            'Ne partagez jamais de mots de passe, de codes Mobile Money ni de données sensibles sur vos clients.',
            "Relisez avant de publier : l'IA est un assistant, c'est vous qui signez.",
          ],
          en: [
            'Always check figures, dates and legal information: AI can be wrong with great confidence.',
            'Never share passwords, mobile money PINs or sensitive customer data.',
            'Proofread before publishing: AI is an assistant, you are the one signing.',
          ],
        },
      },
    ],
  },
  {
    slug: 'whatsapp-business-guide-complet',
    category: 'vente',
    tools: ['WhatsApp Business'],
    updated: '2026-11-17',
    pinned: true,
    title: {
      fr: 'WhatsApp Business : le guide complet pour vendre plus',
      en: 'WhatsApp Business: the complete guide to selling more',
    },
    description: {
      fr: 'Profil, catalogue, réponses rapides, étiquettes, statut : configurez WhatsApp Business comme une vraie boutique en une heure.',
      en: 'Profile, catalogue, quick replies, labels, status: set up WhatsApp Business like a real shop in one hour.',
    },
    intro: {
      fr: "Pour beaucoup de commerces en Côte d'Ivoire, WhatsApp est déjà le premier canal de vente. WhatsApp Business est gratuit et ajoute les outils d'une vraie boutique. Voici comment bien le configurer.",
      en: 'For many businesses in Côte d\'Ivoire and across Africa, WhatsApp is already the main sales channel. WhatsApp Business is free and adds the tools of a real shop. Here is how to set it up properly.',
    },
    sections: [
      {
        title: { fr: 'Un profil qui inspire confiance', en: 'A profile that builds trust' },
        text: {
          fr: "Dans les outils professionnels de l'application, remplissez votre profil en entier :",
          en: "In the app's business tools, fill in your whole profile:",
        },
        bullets: {
          fr: [
            'Photo : votre logo ou une photo nette de votre boutique.',
            'Description : ce que vous vendez, où et pour qui, en une ou deux phrases.',
            "Adresse et horaires d'ouverture.",
            'Email et lien vers votre site ou votre page Facebook.',
          ],
          en: [
            'Photo: your logo or a sharp photo of your shop.',
            'Description: what you sell, where and to whom, in one or two sentences.',
            'Address and opening hours.',
            'Email and a link to your website or Facebook page.',
          ],
        },
      },
      {
        title: { fr: 'Le catalogue : votre vitrine dans le téléphone', en: 'The catalogue: your shop window in the phone' },
        text: {
          fr: "Ajoutez vos produits avec photo, prix et description. Vos clients les consultent sans vous écrire, et vous pouvez envoyer un article du catalogue en un clic dans une discussion.",
          en: 'Add your products with a photo, price and description. Customers can browse them without messaging you, and you can share a catalogue item in a chat in one tap.',
        },
        bullets: {
          fr: [
            'Des photos nettes, sur fond clair, à la lumière du jour.',
            'Un prix clair (ou « à partir de »).',
            'Une description courte : taille, couleur, délai de livraison.',
          ],
          en: [
            'Sharp photos, on a light background, in daylight.',
            'A clear price (or "from").',
            'A short description: size, colour, delivery time.',
          ],
        },
        prompt: {
          fr: 'Rédige une description de produit pour mon catalogue WhatsApp. Produit : [nom]. Caractéristiques : [taille, matière, couleur]. Prix : [prix] FCFA. Clientèle : [qui]. Style : simple et vendeur, 3 lignes maximum.',
          en: 'Write a product description for my WhatsApp catalogue. Product: [name]. Features: [size, material, colour]. Price: [price]. Customers: [who]. Style: simple and persuasive, 3 lines max.',
        },
      },
      {
        title: { fr: 'Messages automatiques et réponses rapides', en: 'Automatic messages and quick replies' },
        bullets: {
          fr: [
            "Message d'accueil : envoyé automatiquement au premier message d'un nouveau client.",
            "Message d'absence : pour répondre en dehors de vos horaires.",
            'Réponses rapides : enregistrez vos réponses fréquentes (frais de livraison, moyens de paiement, adresse) et appelez-les en tapant « / » dans la discussion.',
          ],
          en: [
            "Greeting message: sent automatically when a new customer first writes to you.",
            'Away message: replies for you outside your opening hours.',
            'Quick replies: save your frequent answers (delivery fees, payment methods, address) and call them up by typing "/" in the chat.',
          ],
        },
        prompt: {
          fr: "Écris un message d'accueil WhatsApp pour ma boutique [nom], qui vend [produits] à [ville]. Ton chaleureux, 3 phrases maximum, avec mes horaires [horaires] et une invitation à consulter le catalogue.",
          en: 'Write a WhatsApp greeting message for my shop [name], which sells [products] in [city]. Warm tone, 3 sentences max, with my opening hours [hours] and an invitation to browse the catalogue.',
        },
      },
      {
        title: { fr: 'Les étiquettes pour ne perdre aucune vente', en: 'Labels so you never lose a sale' },
        text: {
          fr: 'Classez vos discussions avec des étiquettes de couleur, par exemple :',
          en: 'Sort your chats with coloured labels, for example:',
        },
        bullets: {
          fr: ['Nouveau client', 'Commande en cours', 'Payé', 'À livrer', 'À relancer'],
          en: ['New customer', 'Order in progress', 'Paid', 'To deliver', 'Follow up'],
        },
        tip: {
          fr: 'Chaque soir, ouvrez l\'étiquette « À relancer » : ce sont des ventes presque faites.',
          en: 'Every evening, open the "Follow up" label: those are sales that are almost done.',
        },
      },
      {
        title: { fr: 'Le statut, votre panneau publicitaire gratuit', en: 'Status: your free billboard' },
        text: {
          fr: 'Vos contacts regardent les statuts tous les jours. Publiez régulièrement : nouveautés, coulisses, avis de clients (avec leur accord), promotions limitées dans le temps.',
          en: 'Your contacts check statuses every day. Post regularly: new arrivals, behind the scenes, customer reviews (with their permission), time-limited offers.',
        },
        tip: {
          fr: 'Partagez le lien direct vers votre WhatsApp (wa.me/ suivi de votre numéro au format international, sans le +) sur Facebook, Instagram et vos cartes de visite.',
          en: 'Share the direct link to your WhatsApp (wa.me/ followed by your number in international format, without the +) on Facebook, Instagram and your business cards.',
        },
      },
    ],
  },
  {
    slug: 'prompts-publications-reseaux-sociaux',
    category: 'reseaux',
    tools: ['ChatGPT', 'Claude', 'Facebook', 'Instagram', 'TikTok'],
    updated: '2026-11-17',
    featured: true,
    title: {
      fr: '10 prompts pour vos publications Facebook et Instagram',
      en: '10 prompts for your Facebook and Instagram posts',
    },
    description: {
      fr: "Des prompts prêts à copier pour ne plus jamais manquer d'idées : annonces, promotions, coulisses, avis clients, vidéos courtes.",
      en: 'Copy-ready prompts so you never run out of ideas: announcements, promotions, behind the scenes, reviews, short videos.',
    },
    intro: {
      fr: 'Remplacez simplement ce qui est entre crochets par vos informations. Chaque prompt fonctionne avec ChatGPT, Claude ou Gemini.',
      en: 'Simply replace what is in square brackets with your own details. Every prompt works with ChatGPT, Claude or Gemini.',
    },
    sections: [
      {
        title: { fr: 'Trouver des idées pour tout le mois', en: 'Get ideas for the whole month' },
        prompt: {
          fr: "Je gère [type d'activité] à [ville]. Propose un calendrier de 12 publications pour le mois de [mois], avec pour chacune : le jour, le sujet, le type (photo, vidéo, carrousel) et une phrase d'accroche.",
          en: 'I run [type of business] in [city]. Suggest a calendar of 12 posts for [month], each with: the day, the topic, the type (photo, video, carousel) and a hook sentence.',
        },
      },
      {
        title: { fr: 'Annoncer un nouveau produit', en: 'Announce a new product' },
        prompt: {
          fr: 'Rédige une publication Facebook pour annoncer [produit]. Mets en avant [avantage principal]. Commence par une accroche forte, ajoute 3 emojis maximum et termine par une invitation à commander sur WhatsApp.',
          en: 'Write a Facebook post announcing [product]. Highlight [main benefit]. Start with a strong hook, use 3 emojis max and end with an invitation to order on WhatsApp.',
        },
      },
      {
        title: { fr: 'Lancer une promotion', en: 'Launch a promotion' },
        prompt: {
          fr: "Écris 3 versions d'une publication pour une promotion de [réduction] sur [produit], valable jusqu'au [date]. Crée un sentiment d'urgence sans être agressif.",
          en: 'Write 3 versions of a post for a [discount] promotion on [product], valid until [date]. Create a sense of urgency without being pushy.',
        },
      },
      {
        title: { fr: 'Raconter les coulisses', en: 'Show what happens behind the scenes' },
        prompt: {
          fr: 'Aide-moi à écrire une publication qui montre les coulisses de mon activité : [décrivez une étape de votre travail]. Ton authentique, à la première personne, 80 mots maximum.',
          en: 'Help me write a post showing the behind the scenes of my business: [describe a step of your work]. Authentic tone, first person, 80 words max.',
        },
      },
      {
        title: { fr: 'Mettre en valeur un avis client', en: 'Showcase a customer review' },
        prompt: {
          fr: "Voici l'avis d'un client : [collez l'avis]. Transforme-le en publication de remerciement qui donne envie à d'autres clients d'essayer, sans exagérer.",
          en: 'Here is a customer review: [paste the review]. Turn it into a thank-you post that makes other customers want to try, without exaggerating.',
        },
        tip: {
          fr: "Demandez toujours l'accord du client avant de publier son avis ou son nom.",
          en: "Always ask the customer's permission before publishing their review or name.",
        },
      },
      {
        title: { fr: 'Répondre à une question fréquente', en: 'Answer a frequent question' },
        prompt: {
          fr: 'Mes clients me demandent souvent : [question]. Écris une publication éducative courte qui y répond clairement et qui montre mon expertise.',
          en: 'My customers often ask me: [question]. Write a short educational post that answers it clearly and shows my expertise.',
        },
      },
      {
        title: { fr: 'Écrire une légende Instagram avec hashtags', en: 'Write an Instagram caption with hashtags' },
        prompt: {
          fr: "Écris une légende Instagram pour cette photo : [décrivez la photo]. 2 phrases, puis 8 hashtags pertinents mélangeant des hashtags locaux (Abidjan, Côte d'Ivoire) et des hashtags de mon secteur [secteur].",
          en: 'Write an Instagram caption for this photo: [describe the photo]. 2 sentences, then 8 relevant hashtags mixing local hashtags ([your city]) and hashtags from my industry [industry].',
        },
      },
      {
        title: { fr: 'Préparer le script d\'une vidéo courte', en: 'Prepare a short video script' },
        prompt: {
          fr: "Écris le script d'une vidéo de 30 secondes pour TikTok ou Reels sur [sujet]. Découpe-le en : accroche (3 secondes), 3 points clés, appel à l'action. Indique ce qu'on voit à l'écran pour chaque partie.",
          en: 'Write the script for a 30-second TikTok or Reels video about [topic]. Split it into: hook (3 seconds), 3 key points, call to action. Describe what we see on screen for each part.',
        },
      },
      {
        title: { fr: 'Adapter un texte à chaque réseau', en: 'Adapt one text to every network' },
        prompt: {
          fr: 'Voici ma publication Facebook : [collez le texte]. Adapte-la en trois versions : un statut WhatsApp (2 lignes), une légende Instagram et une publication LinkedIn plus professionnelle.',
          en: 'Here is my Facebook post: [paste the text]. Adapt it into three versions: a WhatsApp status (2 lines), an Instagram caption and a more professional LinkedIn post.',
        },
      },
      {
        title: { fr: 'Relire et corriger', en: 'Proofread and correct' },
        prompt: {
          fr: 'Corrige les fautes de ce texte sans changer mon style ni mes expressions : [collez le texte]. Liste ensuite les corrections faites.',
          en: 'Fix the mistakes in this text without changing my style or expressions: [paste the text]. Then list the corrections you made.',
        },
      },
    ],
  },
  {
    slug: 'visuels-pro-canva-ia',
    category: 'reseaux',
    tools: ['Canva', 'ChatGPT', 'Claude'],
    updated: '2026-11-17',
    title: {
      fr: "Créer des visuels pro avec Canva et l'IA",
      en: 'Create professional visuals with Canva and AI',
    },
    description: {
      fr: "Les bons formats, une mini charte graphique et une méthode en 5 étapes pour des visuels qui donnent envie d'acheter.",
      en: 'The right sizes, a simple brand kit and a 5-step method for visuals that make people want to buy.',
    },
    intro: {
      fr: "Pas besoin d'être graphiste pour publier des visuels soignés. Avec Canva (qui a une version gratuite) et un assistant IA, vous pouvez créer une image professionnelle en quelques minutes.",
      en: "You don't need to be a designer to publish polished visuals. With Canva (which has a free version) and an AI assistant, you can create a professional image in minutes.",
    },
    sections: [
      {
        title: { fr: 'Les bons formats', en: 'The right sizes' },
        bullets: {
          fr: [
            'Publication Instagram ou Facebook : 1080 × 1080 px (carré) ou 1080 × 1350 px (portrait).',
            'Story, statut WhatsApp, Reels, TikTok : 1080 × 1920 px (vertical).',
            'Couverture de page Facebook : partez du modèle « Couverture Facebook » de Canva, déjà au bon format.',
          ],
          en: [
            'Instagram or Facebook post: 1080 × 1080 px (square) or 1080 × 1350 px (portrait).',
            'Story, WhatsApp status, Reels, TikTok: 1080 × 1920 px (vertical).',
            'Facebook page cover: start from Canva\'s "Facebook Cover" template, already at the right size.',
          ],
        },
      },
      {
        title: { fr: 'Votre mini charte graphique', en: 'Your mini brand kit' },
        text: {
          fr: 'Pour être reconnu au premier coup d\'œil, décidez une fois pour toutes :',
          en: 'To be recognised at first glance, decide once and for all:',
        },
        bullets: {
          fr: [
            '2 ou 3 couleurs (celles de votre logo).',
            '2 polices maximum : une pour les titres, une pour le texte.',
            "L'emplacement de votre logo (toujours au même endroit).",
          ],
          en: [
            '2 or 3 colours (those of your logo).',
            '2 fonts max: one for headings, one for body text.',
            'Where your logo goes (always in the same spot).',
          ],
        },
        tip: {
          fr: 'Enregistrez vos couleurs et vos polices dans Canva pour les retrouver à chaque création.',
          en: 'Save your colours and fonts in Canva so they are ready every time you create.',
        },
      },
      {
        title: { fr: 'La méthode en 5 étapes', en: 'The 5-step method' },
        ordered: true,
        bullets: {
          fr: [
            'Choisissez un modèle Canva proche de votre idée.',
            'Remplacez les couleurs et les polices par celles de votre charte.',
            'Mettez votre propre photo produit (nette, bien éclairée).',
            'Un seul message par visuel : un titre court, lisible sur téléphone.',
            'Ajoutez votre contact (WhatsApp ou page) en bas du visuel.',
          ],
          en: [
            'Pick a Canva template close to your idea.',
            'Swap the colours and fonts for your brand ones.',
            'Use your own product photo (sharp, well lit).',
            'One message per visual: a short headline, readable on a phone.',
            'Add your contact (WhatsApp or page) at the bottom.',
          ],
        },
      },
      {
        title: { fr: "Laisser l'IA écrire le texte du visuel", en: 'Let AI write the text on your visual' },
        prompt: {
          fr: 'Je crée un visuel pour [produit / événement]. Propose 5 titres percutants de 6 mots maximum, pour un public à [ville]. Puis une phrase secondaire de 10 mots maximum.',
          en: 'I am creating a visual for [product / event]. Suggest 5 punchy headlines of 6 words max, for an audience in [city]. Then a secondary line of 10 words max.',
        },
      },
      {
        title: { fr: 'Les erreurs à éviter', en: 'Mistakes to avoid' },
        bullets: {
          fr: [
            "Trop de texte : si on ne le lit pas en 3 secondes, c'est trop.",
            'Des photos floues ou sombres.',
            'Trop de couleurs et de polices différentes.',
            "Des images trouvées sur Internet sans en avoir les droits : utilisez vos photos ou la bibliothèque de Canva.",
          ],
          en: [
            "Too much text: if it can't be read in 3 seconds, it's too much.",
            'Blurry or dark photos.',
            'Too many different colours and fonts.',
            "Images found online that you don't have the rights to: use your own photos or Canva's library.",
          ],
        },
      },
    ],
  },
  {
    slug: 'commerce-sur-google-maps',
    category: 'web',
    tools: ['Google Maps', 'Google Business Profile'],
    updated: '2026-11-17',
    title: {
      fr: 'Votre commerce sur Google Maps en 30 minutes',
      en: 'Put your business on Google Maps in 30 minutes',
    },
    description: {
      fr: 'Créez votre fiche Google gratuite pour que les clients du quartier vous trouvent, vous appellent et viennent jusqu\'à vous.',
      en: 'Create your free Google listing so local customers can find you, call you and get to your door.',
    },
    intro: {
      fr: 'Quand un client cherche « pharmacie Cocody » ou « couturier Yopougon », Google affiche les commerces présents sur la carte. Votre fiche d\'établissement Google (Google Business Profile) est gratuite. Voici comment la créer et la rendre efficace.',
      en: 'When a customer searches for "pharmacy near me" or "tailor Cocody", Google shows the businesses on the map. Your Google Business Profile is free. Here is how to create it and make it work for you.',
    },
    sections: [
      {
        title: { fr: 'Créer la fiche', en: 'Create the listing' },
        ordered: true,
        bullets: {
          fr: [
            'Connectez-vous avec un compte Gmail sur google.com/business.',
            'Saisissez le nom exact de votre commerce et choisissez la bonne catégorie.',
            "Placez précisément votre repère sur la carte. À Abidjan, ajoutez un point de repère connu dans l'adresse (« en face de… », « à côté de… »).",
            'Ajoutez votre numéro de téléphone (celui que vous utilisez sur WhatsApp).',
          ],
          en: [
            'Sign in with a Gmail account at google.com/business.',
            'Enter the exact name of your business and choose the right category.',
            'Place your pin precisely on the map. Where street addresses are unclear, add a well-known landmark ("opposite…", "next to…").',
            'Add your phone number (the one you use on WhatsApp).',
          ],
        },
      },
      {
        title: { fr: 'Faire valider la fiche', en: 'Get the listing verified' },
        text: {
          fr: "Google demande de vérifier que vous êtes bien le propriétaire. Selon les cas, la vérification se fait par une courte vidéo de votre local, par téléphone ou par un autre moyen proposé à l'écran. Suivez simplement les étapes indiquées.",
          en: 'Google asks you to confirm that you own the business. Depending on the case, verification is done with a short video of your premises, by phone or by another method shown on screen. Just follow the steps.',
        },
      },
      {
        title: { fr: 'Une fiche complète', en: 'A complete listing' },
        bullets: {
          fr: [
            "Horaires d'ouverture (et jours fériés).",
            'Au moins 5 photos : façade, intérieur, produits, équipe.',
            'Une description claire de vos produits et services.',
            'Vos produits ou services phares, avec les prix si possible.',
          ],
          en: [
            'Opening hours (and public holidays).',
            'At least 5 photos: storefront, inside, products, team.',
            'A clear description of your products and services.',
            'Your flagship products or services, with prices if possible.',
          ],
        },
        prompt: {
          fr: "Rédige la description de ma fiche Google pour [nom du commerce], [activité] situé à [quartier, ville]. 700 caractères maximum, naturelle, avec les mots que mes clients tapent pour me chercher.",
          en: 'Write the description of my Google listing for [business name], a [type of business] located in [neighbourhood, city]. 700 characters max, natural, using the words my customers type to find me.',
        },
      },
      {
        title: { fr: 'Les avis clients', en: 'Customer reviews' },
        text: {
          fr: 'Les avis font remonter votre fiche et rassurent les nouveaux clients.',
          en: 'Reviews push your listing up and reassure new customers.',
        },
        bullets: {
          fr: [
            'Demandez un avis aux clients satisfaits en leur envoyant le lien direct de votre fiche par WhatsApp.',
            'Répondez à chaque avis, positif ou négatif, avec politesse.',
            "N'achetez jamais de faux avis : Google peut les supprimer et pénaliser votre fiche.",
          ],
          en: [
            'Ask happy customers for a review by sending them the direct link to your listing on WhatsApp.',
            'Reply politely to every review, positive or negative.',
            'Never buy fake reviews: Google can remove them and penalise your listing.',
          ],
        },
        prompt: {
          fr: "Un client a laissé cet avis négatif : [collez l'avis]. Rédige une réponse publique calme et professionnelle qui reconnaît le problème et propose une solution, en 4 phrases.",
          en: 'A customer left this negative review: [paste the review]. Write a calm, professional public reply that acknowledges the problem and offers a solution, in 4 sentences.',
        },
      },
    ],
  },
  {
    slug: 'checklist-site-web',
    category: 'web',
    tools: [],
    updated: '2026-11-17',
    title: {
      fr: 'Préparer votre site web : la checklist avant de démarrer',
      en: 'Preparing your website: the checklist before you start',
    },
    description: {
      fr: 'Objectif, pages, textes, photos, nom de domaine : tout ce qu\'il faut réunir pour un site rapide à créer et vraiment utile.',
      en: 'Goal, pages, copy, photos, domain name: everything to gather for a website that is quick to build and genuinely useful.',
    },
    intro: {
      fr: "Un site réussi se prépare avant la première ligne de code. Avec cette checklist, vous gagnerez du temps (et de l'argent) avec n'importe quel prestataire, nous compris.",
      en: 'A successful website is prepared before the first line of code. With this checklist you will save time (and money) with any web provider, including us.',
    },
    sections: [
      {
        title: { fr: "Définir l'objectif", en: 'Define the goal' },
        text: {
          fr: 'Un site doit avoir un objectif principal. Quel est le vôtre ?',
          en: 'A website needs one main goal. What is yours?',
        },
        bullets: {
          fr: [
            'Recevoir des demandes de devis ou des messages WhatsApp.',
            'Vendre en ligne.',
            'Présenter votre activité et rassurer vos clients.',
            'Prendre des rendez-vous.',
          ],
          en: [
            'Receive quote requests or WhatsApp messages.',
            'Sell online.',
            'Present your business and reassure customers.',
            'Take bookings.',
          ],
        },
      },
      {
        title: { fr: 'Lister les pages', en: 'List the pages' },
        bullets: {
          fr: [
            'Accueil : qui vous êtes et ce que vous proposez, en une phrase.',
            'Services ou produits.',
            'À propos : votre histoire, votre équipe.',
            'Réalisations ou avis clients.',
            'Contact : téléphone, WhatsApp, adresse, carte.',
          ],
          en: [
            'Home: who you are and what you offer, in one sentence.',
            'Services or products.',
            'About: your story, your team.',
            'Work samples or customer reviews.',
            'Contact: phone, WhatsApp, address, map.',
          ],
        },
      },
      {
        title: { fr: 'Préparer le contenu', en: 'Prepare the content' },
        bullets: {
          fr: [
            'Votre logo en bonne qualité.',
            'Des photos réelles de vos produits, de votre équipe, de vos locaux.',
            'Les textes de chaque page (même imparfaits : ils seront retravaillés).',
            'Vos coordonnées et les liens vers vos réseaux sociaux.',
          ],
          en: [
            'Your logo in good quality.',
            'Real photos of your products, team and premises.',
            'The text for each page (even if imperfect: it will be polished).',
            'Your contact details and links to your social media.',
          ],
        },
        prompt: {
          fr: "Je prépare le site de mon activité : [description]. Mes clients sont [qui]. Rédige le texte de la page d'accueil : un titre accrocheur, une phrase d'explication, 3 points forts et un appel à l'action vers WhatsApp.",
          en: 'I am preparing the website for my business: [description]. My customers are [who]. Write the home page copy: a catchy headline, one explanatory sentence, 3 key strengths and a call to action to WhatsApp.',
        },
      },
      {
        title: { fr: 'Choisir un nom de domaine', en: 'Choose a domain name' },
        bullets: {
          fr: [
            'Court, facile à dire au téléphone et à écrire.',
            'Le nom de votre marque si possible.',
            "Extension : .com pour un public international, .ci pour montrer votre ancrage en Côte d'Ivoire.",
            'Évitez les tirets et les chiffres.',
          ],
          en: [
            'Short, easy to say over the phone and to type.',
            'Your brand name if possible.',
            'Extension: .com for an international audience, or your country extension (such as .ci) to show local roots.',
            'Avoid hyphens and numbers.',
          ],
        },
      },
      {
        title: { fr: "Penser au téléphone d'abord", en: 'Think mobile first' },
        text: {
          fr: 'La plupart de vos visiteurs viendront probablement d\'un téléphone, parfois avec une connexion limitée.',
          en: 'Most of your visitors will probably come from a phone, sometimes on a limited connection.',
        },
        bullets: {
          fr: [
            'Des pages légères qui chargent vite.',
            'Un bouton WhatsApp visible sur chaque page.',
            'Des textes courts et lisibles.',
          ],
          en: [
            'Light pages that load fast.',
            'A visible WhatsApp button on every page.',
            'Short, readable text.',
          ],
        },
      },
    ],
  },
  {
    slug: 'devis-factures-avec-l-ia',
    category: 'ia',
    tools: ['ChatGPT', 'Claude', 'Google Docs', 'Word'],
    updated: '2026-11-17',
    title: {
      fr: "Faire ses devis et factures plus vite avec l'IA",
      en: 'Write quotes and invoices faster with AI',
    },
    description: {
      fr: 'Des prompts pour rédiger des devis clairs, créer vos modèles et relancer un paiement avec tact.',
      en: 'Prompts to write clear quotes, build your templates and chase a payment tactfully.',
    },
    intro: {
      fr: "Un devis clair rassure et fait signer plus vite. L'IA peut vous aider à le rédiger et à le présenter, à condition de toujours vérifier les montants vous-même.",
      en: 'A clear quote reassures customers and gets signed faster. AI can help you write and lay it out, as long as you always check the amounts yourself.',
    },
    sections: [
      {
        title: { fr: "Ce qu'un bon devis doit contenir", en: 'What a good quote must include' },
        bullets: {
          fr: [
            'Vos coordonnées et celles du client.',
            'La date et un numéro de devis.',
            'Le détail des prestations ou des produits, avec quantités et prix unitaires.',
            'Le total, les conditions de paiement (acompte, Mobile Money, virement) et la durée de validité.',
            'Le délai de réalisation ou de livraison.',
          ],
          en: [
            "Your contact details and the customer's.",
            'The date and a quote number.',
            'The breakdown of services or products, with quantities and unit prices.',
            'The total, payment terms (deposit, mobile money, bank transfer) and how long the quote is valid.',
            'The completion or delivery time.',
          ],
        },
        tip: {
          fr: 'Les mentions légales obligatoires dépendent de votre statut et de votre pays : faites-les vérifier une fois par un comptable.',
          en: 'Mandatory legal information depends on your business status and country: have it checked once by an accountant.',
        },
      },
      {
        title: { fr: "Rédiger le devis avec l'IA", en: 'Write the quote with AI' },
        prompt: {
          fr: 'Tu es mon assistant administratif. Rédige un devis professionnel pour [client], pour les prestations suivantes : [liste avec quantités et prix en FCFA]. Ajoute un acompte de [x] %, une validité de [x] jours et un délai de [x]. Présente-le sous forme de tableau, puis calcule le total.',
          en: 'You are my admin assistant. Write a professional quote for [customer] for the following services: [list with quantities and prices]. Add a [x]% deposit, a validity of [x] days and a timeline of [x]. Present it as a table, then calculate the total.',
        },
        tip: {
          fr: "Recalculez toujours le total vous-même : l'IA peut faire des erreurs de calcul.",
          en: 'Always recalculate the total yourself: AI can make arithmetic mistakes.',
        },
      },
      {
        title: { fr: 'Créer un modèle réutilisable', en: 'Build a reusable template' },
        text: {
          fr: "Une fois le devis satisfaisant, demandez à l'IA d'en faire un modèle avec des champs à remplir, puis copiez-le dans Google Docs ou Word. Vous n'aurez plus qu'à changer les informations à chaque fois.",
          en: 'Once the quote looks right, ask the AI to turn it into a template with fields to fill in, then copy it into Google Docs or Word. Next time, you only change the details.',
        },
        prompt: {
          fr: 'Transforme ce devis en modèle réutilisable, avec des champs entre crochets pour les informations qui changent : [collez le devis].',
          en: 'Turn this quote into a reusable template, with fields in square brackets for the details that change: [paste the quote].',
        },
      },
      {
        title: { fr: 'Relancer un paiement avec tact', en: 'Chase a payment tactfully' },
        prompt: {
          fr: 'Rédige un message WhatsApp courtois pour rappeler à [client] que la facture n° [numéro] de [montant] FCFA est arrivée à échéance le [date]. Rappelle les moyens de paiement : [moyens]. Ton cordial mais ferme, 4 phrases maximum.',
          en: 'Write a courteous WhatsApp message reminding [customer] that invoice no. [number] for [amount] was due on [date]. Restate the payment methods: [methods]. Friendly but firm, 4 sentences max.',
        },
      },
    ],
  },
  {
    slug: 'relancer-clients-whatsapp',
    category: 'vente',
    tools: ['WhatsApp Business', 'ChatGPT', 'Claude'],
    updated: '2026-11-17',
    title: {
      fr: 'Relancer vos clients sur WhatsApp sans les déranger',
      en: 'Follow up with customers on WhatsApp without bothering them',
    },
    description: {
      fr: 'Quand, comment et avec quels messages relancer un client qui hésite, sans passer pour un vendeur insistant.',
      en: 'When, how and with which messages to follow up with a hesitant customer, without coming across as pushy.',
    },
    intro: {
      fr: "Beaucoup de ventes se perdent simplement parce que personne n'a relancé. Une bonne relance est utile au client : elle répond à une question, rappelle une offre ou apporte une nouveauté.",
      en: 'Many sales are lost simply because nobody followed up. A good follow-up is useful to the customer: it answers a question, recalls an offer or brings something new.',
    },
    sections: [
      {
        title: { fr: 'Les bons moments pour relancer', en: 'The right moments to follow up' },
        bullets: {
          fr: [
            'Le lendemain d\'une demande de prix restée sans réponse.',
            'Quand un produit demandé revient en stock.',
            'Quelques jours après une livraison, pour demander si tout va bien.',
            'Avant une période de fêtes ou la rentrée scolaire.',
          ],
          en: [
            'The day after a price request that got no reply.',
            'When a requested product is back in stock.',
            'A few days after a delivery, to check everything is fine.',
            'Before a holiday season or back-to-school.',
          ],
        },
      },
      {
        title: { fr: "Les règles d'une bonne relance", en: 'The rules of a good follow-up' },
        bullets: {
          fr: [
            'Un message court et personnel, avec le prénom du client.',
            'Une seule relance après une demande sans réponse, puis laissez le client revenir vers vous.',
            'Apportez toujours quelque chose : une information, une photo, une offre.',
            'Respectez les horaires : pas de message tard le soir.',
          ],
          en: [
            "A short, personal message using the customer's first name.",
            'Only one follow-up after an unanswered request, then let the customer come back to you.',
            'Always bring something: information, a photo, an offer.',
            'Respect the time of day: no messages late at night.',
          ],
        },
      },
      {
        title: { fr: '3 messages de relance prêts à adapter', en: '3 follow-up messages ready to adapt' },
        prompt: {
          fr: "Écris 3 messages de relance WhatsApp pour un client qui m'a demandé le prix de [produit] il y a [x] jours sans répondre. Chaque message fait 2 phrases maximum, reste amical et apporte quelque chose de nouveau : une photo, une disponibilité ou un petit avantage.",
          en: 'Write 3 WhatsApp follow-up messages for a customer who asked me the price of [product] [x] days ago and never replied. Each message is 2 sentences max, stays friendly and brings something new: a photo, availability or a small perk.',
        },
      },
      {
        title: { fr: 'Après la vente : fidéliser', en: 'After the sale: build loyalty' },
        prompt: {
          fr: "Écris un message WhatsApp à envoyer 3 jours après la livraison de [produit] pour demander au client s'il est satisfait, l'inviter à laisser un avis et lui rappeler qu'il peut commander à nouveau quand il veut. Ton chaleureux, 3 phrases.",
          en: 'Write a WhatsApp message to send 3 days after delivering [product], asking the customer if they are happy, inviting them to leave a review and reminding them they can order again anytime. Warm tone, 3 sentences.',
        },
      },
      {
        title: { fr: 'Les listes de diffusion', en: 'Broadcast lists' },
        text: {
          fr: "Pour annoncer une nouveauté à plusieurs clients à la fois, utilisez les listes de diffusion de WhatsApp Business : chaque client reçoit le message en privé, comme un message personnel. Seules les personnes qui ont enregistré votre numéro reçoivent vos diffusions.",
          en: 'To announce something new to several customers at once, use WhatsApp Business broadcast lists: each customer receives the message privately, like a personal message. Only people who have saved your number receive your broadcasts.',
        },
        tip: {
          fr: 'Créez une liste par type de client (grossistes, particuliers, clients fidèles) pour envoyer le bon message aux bonnes personnes.',
          en: 'Create one list per type of customer (wholesalers, individuals, loyal customers) to send the right message to the right people.',
        },
      },
    ],
  },
  {
    slug: 'prompts-images-ia',
    category: 'images',
    tools: ['ChatGPT', 'Gemini', 'Canva'],
    updated: '2026-11-17',
    title: {
      fr: "15 prompts d'images IA pour vos produits et vos affiches",
      en: '15 AI image prompts for your products and posters',
    },
    description: {
      fr: "La formule d'un bon prompt d'image, puis 15 prompts prêts à copier : photos produits, plats, affiches, portraits et illustrations.",
      en: 'The formula for a good image prompt, then 15 copy-ready prompts: product shots, food, posters, portraits and illustrations.',
    },
    intro: {
      fr: "Les outils comme ChatGPT, Gemini ou Canva peuvent créer des images à partir d'un simple texte. La différence entre une image banale et une image qui vend tient souvent en quelques mots bien choisis. Remplacez ce qui est entre crochets par vos informations.",
      en: 'Tools like ChatGPT, Gemini or Canva can create images from a simple text. The difference between a dull image and one that sells often comes down to a few well-chosen words. Replace what is in square brackets with your own details.',
    },
    sections: [
      {
        title: { fr: "La formule d'un bon prompt d'image", en: 'The formula for a good image prompt' },
        text: {
          fr: 'Décrivez votre image comme si vous la commandiez à un photographe. Donnez ces 6 éléments :',
          en: 'Describe your image as if you were briefing a photographer. Give these 6 elements:',
        },
        bullets: {
          fr: [
            'Sujet : ce qu\'on voit (« un flacon d\'huile de karité »).',
            'Décor : où il se trouve (« sur un tissu wax, table en bois »).',
            'Lumière : « lumière douce du matin », « éclairage de studio ».',
            'Cadrage : gros plan, vue de dessus, plan large.',
            'Style : photo réaliste, illustration, affiche moderne.',
            'Format : carré (1:1) pour une publication, vertical (9:16) pour une story ou un statut.',
          ],
          en: [
            'Subject: what we see ("a bottle of shea butter oil").',
            'Setting: where it is ("on wax-print fabric, wooden table").',
            'Lighting: "soft morning light", "studio lighting".',
            'Framing: close-up, top-down view, wide shot.',
            'Style: realistic photo, illustration, modern poster.',
            'Format: square (1:1) for a post, vertical (9:16) for a story or status.',
          ],
        },
      },
      {
        title: { fr: 'Photos de produits', en: 'Product photos' },
        prompts: [
          {
            fr: 'Photo de produit professionnelle de [produit] sur fond blanc uni, éclairage de studio doux, ombre légère sous le produit, netteté parfaite, format carré.',
            en: 'Professional product photo of [product] on a plain white background, soft studio lighting, light shadow under the product, perfectly sharp, square format.',
          },
          {
            fr: 'Mise en scène de [produit] sur une table en bois, à côté de [accessoires], lumière naturelle du matin venant d\'une fenêtre, ambiance chaleureuse, photo réaliste, format vertical 4:5.',
            en: 'Lifestyle shot of [product] on a wooden table next to [props], natural morning light from a window, warm atmosphere, realistic photo, vertical 4:5 format.',
          },
          {
            fr: 'Vue de dessus (flat lay) de [produits cosmétiques] posés sur un tissu wax aux couleurs [couleurs], feuilles vertes et fleurs autour, lumière douce, style magazine beauté.',
            en: 'Top-down flat lay of [cosmetic products] on wax-print fabric in [colours], green leaves and flowers around, soft light, beauty magazine style.',
          },
          {
            fr: 'Photo appétissante de [plat], servi dans [assiette / calebasse], gros plan légèrement en biais, vapeur visible, lumière chaude, arrière-plan flou de restaurant, photo culinaire réaliste.',
            en: 'Mouth-watering photo of [dish] served in [plate / bowl], slightly angled close-up, visible steam, warm light, blurred restaurant background, realistic food photography.',
          },
          {
            fr: 'Photo de [vêtement] porté par un mannequin africain souriant, dans une rue colorée d\'Abidjan, lumière de fin d\'après-midi, plan en pied, style catalogue de mode.',
            en: 'Photo of [clothing item] worn by a smiling African model on a colourful street, late-afternoon light, full-length shot, fashion catalogue style.',
          },
          {
            fr: 'Gros plan sur une main qui tient [produit], devant un fond flou de [lieu : marché, boutique, bureau], lumière naturelle, photo réaliste pour une publicité sur les réseaux sociaux.',
            en: 'Close-up of a hand holding [product] against a blurred background of [place: market, shop, office], natural light, realistic photo for a social media ad.',
          },
        ],
        tip: {
          fr: "Pour garder votre vrai produit, envoyez sa photo à l'outil et demandez de changer seulement le décor. Ne publiez jamais une image qui montre un produit différent de celui que vous vendez : vos clients doivent recevoir ce qu'ils ont vu.",
          en: "To keep your real product, upload its photo to the tool and ask it to change only the background. Never publish an image showing a product different from what you sell: customers must receive what they saw.",
        },
      },
      {
        title: { fr: 'Affiches et visuels promotionnels', en: 'Posters and promotional visuals' },
        prompts: [
          {
            fr: 'Fond d\'affiche moderne pour une promotion de [type de commerce], couleurs [couleurs de votre marque], formes géométriques, grand espace vide en haut pour ajouter un titre, format vertical 9:16, sans texte.',
            en: 'Modern poster background for a [type of business] promotion, colours [your brand colours], geometric shapes, large empty space at the top for a headline, vertical 9:16 format, no text.',
          },
          {
            fr: 'Visuel festif pour [fête / événement], ambiance joyeuse, confettis et lumières, couleurs [couleurs], place libre au centre pour un message, format carré, sans texte.',
            en: 'Festive visual for [holiday / event], joyful mood, confetti and lights, colours [colours], free space in the centre for a message, square format, no text.',
          },
          {
            fr: 'Bannière pour la page Facebook de [nom du commerce], qui vend [produits], style épuré et professionnel, couleurs [couleurs], format paysage très large, sans texte.',
            en: 'Banner for the Facebook page of [business name], which sells [products], clean professional style, colours [colours], very wide landscape format, no text.',
          },
          {
            fr: "Visuel pour annoncer l'ouverture d'une nouvelle boutique de [activité] : façade lumineuse et accueillante, ruban rouge prêt à être coupé, ambiance festive, couleurs [couleurs], format carré, sans texte.",
            en: 'Visual announcing the opening of a new [type of business] shop: bright, welcoming storefront, red ribbon ready to be cut, festive mood, colours [colours], square format, no text.',
          },
        ],
        tip: {
          fr: "Les IA d'images écrivent encore souvent des lettres déformées. Demandez une image « sans texte », puis ajoutez vos titres, prix et numéro dans Canva.",
          en: 'Image AIs still often produce garbled lettering. Ask for an image "with no text", then add your headlines, prices and number in Canva.',
        },
      },
      {
        title: { fr: 'Portraits et illustrations', en: 'Portraits and illustrations' },
        prompts: [
          {
            fr: 'Illustration colorée et moderne d\'une commerçante africaine qui utilise son smartphone dans sa boutique, style dessin plat (flat design), couleurs vives, pour une publication sur [sujet].',
            en: 'Colourful, modern illustration of an African shopkeeper using her smartphone in her shop, flat design style, bright colours, for a post about [topic].',
          },
          {
            fr: 'Icône simple et moderne représentant [service], style ligne fine, couleur [couleur] sur fond blanc, pour un site web.',
            en: 'Simple, modern icon representing [service], thin line style, [colour] on a white background, for a website.',
          },
          {
            fr: 'Personnage mascotte sympathique pour [marque], qui [action], style illustration 3D douce, fond uni [couleur].',
            en: 'Friendly mascot character for [brand], who is [action], soft 3D illustration style, plain [colour] background.',
          },
        ],
        tip: {
          fr: "Ne créez jamais d'image réaliste d'une personne réelle (client, célébrité, concurrent) sans son accord.",
          en: 'Never create a realistic image of a real person (customer, celebrity, competitor) without their consent.',
        },
      },
      {
        title: { fr: 'Corriger une image au lieu de recommencer', en: 'Fix an image instead of starting over' },
        text: {
          fr: 'Si le résultat est presque bon, demandez une correction précise dans la même conversation :',
          en: 'If the result is almost right, ask for a precise fix in the same conversation:',
        },
        bullets: {
          fr: [
            '« Garde tout, mais rends la lumière plus chaude. »',
            '« Même image, avec un fond vert clair. »',
            '« Rapproche le cadrage sur le produit. »',
            '« Retire l\'objet à gauche. »',
          ],
          en: [
            '"Keep everything, but make the light warmer."',
            '"Same image, with a light green background."',
            '"Frame tighter on the product."',
            '"Remove the object on the left."',
          ],
        },
        prompts: [
          {
            fr: 'Même image, au format vertical 9:16, en laissant de l\'espace libre en haut et en bas pour du texte.',
            en: 'Same image in vertical 9:16 format, leaving free space at the top and bottom for text.',
          },
          {
            fr: 'Propose 3 variantes de cette image : une plus lumineuse, une plus sobre et une plus colorée.',
            en: 'Suggest 3 variations of this image: one brighter, one more understated and one more colourful.',
          },
        ],
      },
    ],
  },
  {
    slug: 'videos-reels-tiktok-avec-l-ia',
    category: 'video',
    tools: ['CapCut', 'ChatGPT', 'Claude'],
    updated: '2026-11-17',
    title: {
      fr: "Faire vos vidéos Reels et TikTok avec l'IA",
      en: 'Make your Reels and TikTok videos with AI',
    },
    description: {
      fr: 'Du script au montage : écrire, tourner au téléphone, sous-titrer et découper vos meilleures vidéos courtes avec l\'aide de l\'IA.',
      en: 'From script to edit: write, film on your phone, caption and cut your best short videos with the help of AI.',
    },
    intro: {
      fr: "Les vidéos courtes sont l'un des meilleurs moyens de vous faire connaître gratuitement. Avec un téléphone, une application de montage et un assistant IA, vous pouvez publier des vidéos propres sans équipe ni studio.",
      en: 'Short videos are one of the best ways to get known for free. With a phone, an editing app and an AI assistant, you can publish clean videos without a team or a studio.',
    },
    sections: [
      {
        title: { fr: "Écrire une série de vidéos avec l'IA", en: 'Write a video series with AI' },
        text: {
          fr: 'Pensez en série plutôt qu\'en vidéo isolée : vous gagnez du temps et vos abonnés savent quoi attendre.',
          en: 'Think in series rather than one-off videos: you save time and your followers know what to expect.',
        },
        prompt: {
          fr: "Je suis [métier] à [ville] et mes clients sont [qui]. Propose une série de 5 vidéos courtes (30 secondes) sur le thème [thème]. Pour chaque vidéo : une accroche à dire dans les 3 premières secondes, 3 points clés et une phrase de fin qui invite à m'écrire sur WhatsApp.",
          en: 'I am a [profession] in [city] and my customers are [who]. Suggest a series of 5 short videos (30 seconds) on [theme]. For each video: a hook to say in the first 3 seconds, 3 key points and a closing line inviting people to message me on WhatsApp.',
        },
      },
      {
        title: { fr: 'Bien tourner avec son téléphone', en: 'Film well with your phone' },
        bullets: {
          fr: [
            'Filmez à la verticale, téléphone posé ou sur un petit trépied.',
            'Placez-vous face à une fenêtre : la lumière du jour est la meilleure et elle est gratuite.',
            'Soignez le son : une pièce calme ou un petit micro-cravate change tout.',
            'Tournez par petites phrases : c\'est plus facile à monter et vous pouvez refaire une phrase ratée.',
            'Regardez l\'objectif, pas votre visage à l\'écran.',
          ],
          en: [
            'Film vertically, with the phone propped up or on a small tripod.',
            'Face a window: daylight is the best light and it is free.',
            'Look after the sound: a quiet room or a small lavalier mic changes everything.',
            'Film in short sentences: easier to edit, and you can redo a bad line.',
            'Look at the lens, not at your face on the screen.',
          ],
        },
      },
      {
        title: { fr: 'Trouver vos meilleurs passages', en: 'Find your best moments' },
        text: {
          fr: "Pour une vidéo longue (live, formation, interview), récupérez la transcription (beaucoup d'applications de montage la génèrent) et laissez l'IA repérer les meilleurs extraits.",
          en: 'For a long video (live, training, interview), get the transcript (many editing apps generate one) and let the AI spot the best clips.',
        },
        prompt: {
          fr: 'Voici la transcription de ma vidéo : [collez la transcription]. Indique les 3 passages de 15 à 45 secondes les plus forts pour en faire des Reels, avec la première et la dernière phrase de chaque passage, et propose un titre accrocheur pour chacun.',
          en: 'Here is the transcript of my video: [paste the transcript]. Identify the 3 strongest 15-to-45-second passages to turn into Reels, with the first and last sentence of each, and suggest a catchy title for each one.',
        },
      },
      {
        title: { fr: 'Sous-titres et texte à l\'écran', en: 'Captions and on-screen text' },
        text: {
          fr: "Beaucoup de gens regardent les vidéos sans le son. Les applications comme CapCut créent des sous-titres automatiques en quelques secondes.",
          en: 'Many people watch videos with the sound off. Apps like CapCut create automatic captions in seconds.',
        },
        bullets: {
          fr: [
            'Relisez toujours les sous-titres : les noms propres et les mots locaux sont souvent mal écrits.',
            'Des lettres grosses et contrastées, placées au centre de l\'écran (pas tout en bas, cachées par les boutons).',
            'Un titre à l\'écran dès la première seconde pour dire de quoi parle la vidéo.',
          ],
          en: [
            'Always proofread captions: names and local words are often misspelled.',
            'Large, high-contrast letters, placed in the middle of the screen (not at the very bottom, hidden by the buttons).',
            'An on-screen title from the very first second saying what the video is about.',
          ],
        },
      },
      {
        title: { fr: 'Musique et droits', en: 'Music and rights' },
        bullets: {
          fr: [
            "Utilisez les musiques proposées par l'application ou par le réseau social : elles sont prévues pour ça.",
            'Évitez de mettre une chanson connue téléchargée ailleurs : votre vidéo peut être rendue muette ou bloquée.',
            'Gardez la musique basse sous la voix.',
          ],
          en: [
            'Use the music offered by the app or the social network: it is licensed for that.',
            'Avoid adding a well-known song downloaded elsewhere: your video can be muted or blocked.',
            'Keep the music low under your voice.',
          ],
        },
      },
      {
        title: { fr: 'Publier régulièrement', en: 'Post regularly' },
        text: {
          fr: 'La régularité compte plus que la perfection. Fixez-vous un rythme tenable, par exemple 2 vidéos par semaine, et tournez-en plusieurs le même jour.',
          en: 'Consistency matters more than perfection. Set a pace you can keep, for example 2 videos a week, and film several on the same day.',
        },
        prompt: {
          fr: 'Écris une légende courte pour ma vidéo sur [sujet], avec une question pour faire réagir en commentaire et 5 hashtags adaptés à [ville / secteur].',
          en: 'Write a short caption for my video about [topic], with a question to spark comments and 5 hashtags suited to [city / industry].',
        },
      },
    ],
  },
  {
    slug: 'voix-off-ia',
    category: 'video',
    tools: ['ElevenLabs', 'CapCut', 'ChatGPT'],
    updated: '2026-11-17',
    title: {
      fr: "Créer une voix off avec l'IA pour vos vidéos et vos pubs",
      en: 'Create an AI voiceover for your videos and ads',
    },
    description: {
      fr: 'Écrire un texte fait pour être dit, choisir la bonne voix, la diriger et respecter les règles, pour une voix off naturelle en français.',
      en: 'Write a script made to be spoken, pick the right voice, direct it and follow the rules, for a natural-sounding voiceover.',
    },
    intro: {
      fr: "Une voix off générée par l'IA permet de produire des vidéos sans vous montrer, de faire une publicité en quelques minutes ou de proposer une version anglaise de vos contenus. Bien utilisée, elle sonne naturelle. Voici la méthode.",
      en: 'An AI-generated voiceover lets you produce videos without appearing on camera, make an ad in minutes or offer an English version of your content. Used well, it sounds natural. Here is the method.',
    },
    sections: [
      {
        title: { fr: 'Quand utiliser une voix IA', en: 'When to use an AI voice' },
        bullets: {
          fr: [
            'Une chaîne « sans visage » : tutoriels, astuces, histoires.',
            'Une publicité courte ou une annonce de promotion.',
            'Une version de vos vidéos dans une autre langue.',
          ],
          en: [
            'A "faceless" channel: tutorials, tips, stories.',
            'A short ad or a promotion announcement.',
            'A version of your videos in another language.',
          ],
        },
        tip: {
          fr: 'Pour parler de vous et de votre entreprise, votre vraie voix reste la plus convaincante : les clients achètent aussi une personne.',
          en: 'To talk about yourself and your business, your real voice is still the most convincing: customers also buy into a person.',
        },
      },
      {
        title: { fr: "Écrire un texte fait pour l'oral", en: 'Write a script made to be spoken' },
        text: {
          fr: "Un texte écrit pour être lu des yeux sonne faux à l'oral. Demandez à l'IA de le réécrire :",
          en: 'A text written to be read silently sounds wrong out loud. Ask the AI to rewrite it:',
        },
        prompt: {
          fr: "Réécris ce texte pour qu'il soit dit à voix haute en [durée] secondes environ : phrases courtes, mots simples, ton [chaleureux / dynamique / sérieux], une idée par phrase. Ajoute des points de suspension là où il faut marquer une pause. Texte : [collez votre texte].",
          en: 'Rewrite this text to be spoken out loud in about [duration] seconds: short sentences, simple words, [warm / upbeat / serious] tone, one idea per sentence. Add ellipses where there should be a pause. Text: [paste your text].',
        },
      },
      {
        title: { fr: "Choisir l'outil et la voix", en: 'Choose the tool and the voice' },
        bullets: {
          fr: [
            'ElevenLabs : des voix très réalistes et un grand choix de voix en français.',
            'CapCut : une synthèse vocale simple, directement dans votre montage.',
            'Testez 3 ou 4 voix avec le même texte avant de choisir, puis gardez toujours la même : elle devient la voix de votre marque.',
          ],
          en: [
            'ElevenLabs: very realistic voices and a wide choice of voices and languages.',
            'CapCut: simple text-to-speech, right inside your edit.',
            'Test 3 or 4 voices with the same script before choosing, then always keep the same one: it becomes your brand voice.',
          ],
        },
        tip: {
          fr: "Certaines voix prononcent mal les noms locaux. Écrivez-les comme ils se prononcent (par exemple « Yo-pou-gon », « Bin-guer-ville ») jusqu'à obtenir le bon son.",
          en: 'Some voices mispronounce local names. Spell them the way they sound (for example "Yo-poo-gon") until you get the right pronunciation.',
        },
      },
      {
        title: { fr: 'Diriger la voix', en: 'Direct the voice' },
        bullets: {
          fr: [
            'La ponctuation guide le rythme : un point pour une vraie pause, une virgule pour une respiration.',
            'Découpez votre texte en petits paragraphes et générez-les un par un.',
            'Si une phrase est ratée, régénérez seulement cette phrase, pas tout le texte.',
            'Réglez la vitesse : un peu plus lent que ce que vous pensez est souvent plus clair.',
          ],
          en: [
            'Punctuation drives the rhythm: a full stop for a real pause, a comma for a breath.',
            'Split your script into short paragraphs and generate them one by one.',
            'If one sentence is off, regenerate just that sentence, not the whole script.',
            'Adjust the speed: slightly slower than you think is often clearer.',
          ],
        },
      },
      {
        title: { fr: 'Les règles à respecter', en: 'Rules to follow' },
        bullets: {
          fr: [
            "Ne clonez et n'imitez jamais la voix d'une personne sans son accord écrit, même pour rire.",
            "Vérifiez que votre forfait autorise l'usage commercial (publicité, vente) des voix générées.",
            "Restez honnête : n'utilisez pas une voix IA pour faire croire à un faux témoignage de client.",
          ],
          en: [
            "Never clone or imitate someone's voice without their written consent, even as a joke.",
            'Check that your plan allows commercial use (ads, sales) of generated voices.',
            'Stay honest: never use an AI voice to fake a customer testimonial.',
          ],
        },
      },
    ],
  },
  {
    slug: 'emploi-cv-avec-l-ia',
    category: 'carriere',
    tools: ['ChatGPT', 'Claude', 'LinkedIn'],
    updated: '2026-11-17',
    title: {
      fr: "Trouver un emploi avec l'IA : CV, lettre et entretien",
      en: 'Land a job with AI: CV, cover letter and interview',
    },
    description: {
      fr: "Adapter votre CV à chaque offre, écrire une lettre qui sonne juste, vous entraîner à l'entretien et éviter les fausses offres.",
      en: 'Tailor your CV to each job offer, write a cover letter that sounds right, practise for interviews and avoid fake offers.',
    },
    intro: {
      fr: "L'IA ne remplace pas votre expérience, mais elle vous aide à la présenter beaucoup mieux, et plus vite. Règle d'or de ce guide : ne laissez jamais l'IA inventer un diplôme, un poste ou une compétence que vous n'avez pas.",
      en: "AI doesn't replace your experience, but it helps you present it much better, and faster. The golden rule of this guide: never let the AI invent a degree, a job or a skill you don't have.",
    },
    sections: [
      {
        title: { fr: 'Adapter son CV à chaque offre', en: 'Tailor your CV to each offer' },
        prompt: {
          fr: "Voici mon CV : [collez votre CV]. Voici l'offre d'emploi : [collez l'offre]. 1) Liste les compétences demandées que mon CV ne met pas assez en valeur. 2) Propose une version adaptée de mon CV pour cette offre, sans rien inventer : reformule seulement ce qui est vrai. 3) Liste les questions que tu me poserais pour compléter.",
          en: "Here is my CV: [paste your CV]. Here is the job offer: [paste the offer]. 1) List the required skills my CV doesn't highlight enough. 2) Suggest a version of my CV tailored to this offer, without inventing anything: only rephrase what is true. 3) List the questions you would ask me to fill the gaps.",
        },
        tip: {
          fr: 'Transformez vos tâches en résultats chiffrés quand c\'est possible : « géré la caisse » devient « géré une caisse de [x] clients par jour sans écart ».',
          en: 'Turn duties into measurable results where possible: "handled the till" becomes "handled a till serving [x] customers a day with no discrepancies".',
        },
      },
      {
        title: { fr: 'Passer les logiciels de tri', en: 'Get past screening software' },
        text: {
          fr: 'Beaucoup d\'entreprises et de cabinets trient les CV avec un logiciel avant qu\'un humain les lise. Pour passer ce filtre :',
          en: 'Many companies and recruiters sort CVs with software before a human reads them. To get through:',
        },
        bullets: {
          fr: [
            'Une mise en page simple : pas de tableaux compliqués ni de texte dans des images.',
            "Des titres de rubriques classiques : Expérience, Formation, Compétences.",
            "Reprenez les mots exacts de l'offre quand ils correspondent vraiment à votre parcours.",
            "Envoyez le format demandé (PDF ou Word) et nommez le fichier avec votre nom.",
          ],
          en: [
            'A simple layout: no complex tables and no text inside images.',
            'Standard section headings: Experience, Education, Skills.',
            'Reuse the exact words of the offer when they genuinely match your background.',
            'Send the requested format (PDF or Word) and name the file after yourself.',
          ],
        },
      },
      {
        title: { fr: 'Une lettre de motivation qui vous ressemble', en: 'A cover letter that sounds like you' },
        prompt: {
          fr: "Rédige une lettre de motivation pour le poste de [poste] chez [entreprise]. Mes 3 atouts les plus utiles pour ce poste : [atouts]. Pourquoi cette entreprise m'intéresse : [raison]. Ton professionnel mais naturel, 200 mots maximum, sans formules toutes faites ni exagérations.",
          en: 'Write a cover letter for the [position] role at [company]. My 3 most relevant strengths for this role: [strengths]. Why this company interests me: [reason]. Professional but natural tone, 200 words max, no clichés or exaggerations.',
        },
      },
      {
        title: { fr: "S'entraîner à l'entretien", en: 'Practise for the interview' },
        prompt: {
          fr: "Joue le rôle d'un recruteur pour le poste de [poste] chez [entreprise]. Pose-moi une seule question à la fois et attends ma réponse. Après chaque réponse, donne-moi un retour court : ce qui était bien, ce qu'il faut améliorer et une meilleure formulation. Commence par « Présentez-vous ».",
          en: 'Act as a recruiter for the [position] role at [company]. Ask me one question at a time and wait for my answer. After each answer, give short feedback: what was good, what to improve and a better way to phrase it. Start with "Tell me about yourself".',
        },
        tip: {
          fr: "Répondez à voix haute avant d'écrire votre réponse : c'est comme ça que vous parlerez le jour J.",
          en: "Say your answer out loud before typing it: that's how you will speak on the day.",
        },
      },
      {
        title: { fr: 'Soigner son profil LinkedIn', en: 'Polish your LinkedIn profile' },
        prompt: {
          fr: "Voici mon parcours : [résumé]. Je vise des postes de [poste] à [ville / en télétravail]. Propose 3 titres de profil LinkedIn (220 caractères maximum) et une section « Infos » de 5 lignes, claire et sans jargon.",
          en: 'Here is my background: [summary]. I am targeting [position] roles in [city / remote]. Suggest 3 LinkedIn headlines (220 characters max) and a 5-line "About" section, clear and jargon-free.',
        },
      },
      {
        title: { fr: 'Repérer les fausses offres', en: 'Spot fake job offers' },
        bullets: {
          fr: [
            "Ne payez jamais pour obtenir un emploi : frais de dossier, de formation ou de visa demandés d'avance sont un signal d'alarme.",
            "Méfiez-vous des offres trop belles : gros salaire, aucun entretien, recrutement par WhatsApp uniquement.",
            "N'envoyez pas de pièce d'identité ni de coordonnées bancaires avant d'avoir vérifié que l'entreprise existe vraiment.",
          ],
          en: [
            'Never pay to get a job: application, training or visa fees requested upfront are a red flag.',
            'Be wary of offers that look too good: high pay, no interview, recruitment over WhatsApp only.',
            "Don't send ID documents or bank details before checking that the company really exists.",
          ],
        },
        prompt: {
          fr: "Voici une offre d'emploi que j'ai reçue : [collez l'offre]. Liste les signes qui pourraient indiquer une arnaque et les vérifications que je devrais faire avant de répondre.",
          en: 'Here is a job offer I received: [paste the offer]. List any signs that could point to a scam and the checks I should do before replying.',
        },
      },
    ],
  },
];

export function getGuide(slug: string): Guide | undefined {
  return GUIDES.find(g => g.slug === slug);
}

/** Temps de lecture estimé (≈ 200 mots/minute, 2 min minimum). */
export function readingMinutes(guide: Guide, lang: Lang): number {
  const parts: string[] = [guide.title[lang], guide.description[lang], guide.intro[lang]];
  for (const s of guide.sections) {
    parts.push(s.title[lang]);
    if (s.text) parts.push(s.text[lang]);
    if (s.bullets) parts.push(...s.bullets[lang]);
    if (s.prompt) parts.push(s.prompt[lang]);
    if (s.prompts) parts.push(...s.prompts.map(p => p[lang]));
    if (s.tip) parts.push(s.tip[lang]);
  }
  const words = parts.join(' ').split(/\s+/).filter(Boolean).length;
  return Math.max(2, Math.round(words / 200));
}

export function formatGuideDate(iso: string, lang: Lang): string {
  const [y, m, d] = iso.split('-').map(Number);
  return new Intl.DateTimeFormat(lang === 'fr' ? 'fr-FR' : 'en-US', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    timeZone: 'UTC',
  }).format(new Date(Date.UTC(y, m - 1, d)));
}
