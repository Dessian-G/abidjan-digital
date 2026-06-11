# Abidjan Digital.ia — Site web

Site vitrine professionnel d'**Abidjan Digital.ia**, studio web & IA fondé par Max Guiro.

## Lancer le projet en local

```bash
# 1. Installer les dépendances
npm install

# 2. Remplir les variables d'environnement
# Éditez .env.local avec vos clés (voir section Variables d'environnement ci-dessous)

# 3. Démarrer le serveur de développement
npm run dev
```

Ouvrez [http://localhost:3000](http://localhost:3000) dans votre navigateur.

## Variables d'environnement

Éditez le fichier `.env.local` à la racine avec les clés suivantes :

| Variable | Description | Où l'obtenir |
|---|---|---|
| `ANTHROPIC_API_KEY` | Clé API pour le chatbot Adia (Claude) | [console.anthropic.com](https://console.anthropic.com/) |
| `NEXT_PUBLIC_WEB3FORMS_KEY` | Clé pour l'envoi du formulaire de contact | [web3forms.com](https://web3forms.com/) — entrez `abidjandigitalia@gmail.com` |

> ⚠️ Ne commitez jamais `.env.local` — il est déjà dans `.gitignore`.

## Déploiement sur Vercel

1. Poussez le code sur un dépôt **GitHub**.
2. Connectez-vous sur [vercel.com](https://vercel.com/) et importez le dépôt.
3. Dans **Settings → Environment Variables**, ajoutez :
   - `ANTHROPIC_API_KEY`
   - `NEXT_PUBLIC_WEB3FORMS_KEY`
4. Cliquez **Deploy** — le site sera en ligne en quelques minutes.

## Structure du projet

```
src/
├── app/
│   ├── page.tsx          # Accueil
│   ├── services/         # Page Services
│   ├── a-propos/         # Page À propos
│   ├── contact/          # Page Contact
│   └── api/chat/         # API route chatbot Adia
├── components/
│   ├── Header.tsx        # En-tête + navigation
│   ├── Footer.tsx        # Pied de page
│   ├── ContactForm.tsx   # Formulaire de demande (Web3Forms)
│   └── ChatbotWidget.tsx # Chatbot Adia (bulle flottante)
public/
├── logo.jpg              # Logo de l'agence
└── max-guiro.jpg         # Photo du fondateur
```

## Stack technique

- **Next.js 15** (App Router) + TypeScript
- **Tailwind CSS** pour le style
- **lucide-react** pour les icônes
- **@anthropic-ai/sdk** pour le chatbot Adia
- **Web3Forms** pour l'envoi du formulaire par email
