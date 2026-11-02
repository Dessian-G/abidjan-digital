# CLAUDE.md — Famille WÊ (plugin WordPress)

Plugin WordPress de l'association **Famille WÊ** (diaspora ivoirienne, Dallas Texas &
Oklahoma). Il s'installe sur le site WordPress existant de l'association. Ce dossier est
indépendant du site Next.js « Abidjan Digital.ia » à la racine du dépôt.

Guide utilisateur (installation, usage) : [`README.md`](README.md).

## Fonctionnel

- Rôles : **bureau** = capacité `fwe_manage` (administrateurs + rôle `fwe_bureau`) ;
  **membre** = rôle `fwe_membre`, relié à une fiche de `wp_fwe_members` par `user_id`.
- Cotisation **20 $/mois** (réglage `monthly_amount`), montants en **USD** affichés `$20`, `$1,240`, `$12.50`.
- Retard : voir `includes/class-fwe-dues.php`. Dû chaque mois depuis
  max(mois d'adhésion, `start_period`) jusqu'au mois courant inclus ; le mois courant
  n'est jamais « en retard ». **Alerte quand retard > `late_threshold` (3)**, strictement.
- Alerte auto : cron quotidien `fwe_daily_check` → `FWE_Notifier::run_late_check()`,
  dédoublonnée par `ref = retard-AAAA-MM` (une alerte par membre et par mois).
- Canaux : `in_app`, `email` (`wp_mail`), `sms` / `whatsapp` (Twilio). Chaque envoi est
  journalisé dans `wp_fwe_notifications` avec un statut : `envoye`, `echec`,
  `non_configure`, `ignore` (coordonnée manquante). Ne jamais échouer silencieusement.
- Inscription en ligne → membre `en_attente` (validation bureau), sauf si une fiche
  existe déjà avec le même e-mail : le compte y est rattaché.
- Soutien : cas `naissance` | `mariage` | `deces` ; contributions `declaree` (promise par
  le membre) puis `confirmee` (reçue, confirmée par le bureau).

## Architecture

```
plugin/                      → devient le dossier « famille-we » du zip
  famille-we.php             → en-tête du plugin, constantes FWE_*, chargement, hooks
  uninstall.php              → supprime les données SEULEMENT si delete_on_uninstall
  includes/
    functions.php            → réglages fwe_settings(), périodes AAAA-MM, fwe_money(),
                               libellés fwe_labels(), notices, icônes Lucide fwe_icon()
    class-fwe-install.php    → tables (dbDelta), rôles, page espace, cron
    class-fwe-repo.php       → TOUT l'accès SQL (tables wp_fwe_*)
    class-fwe-dues.php       → calcul de situation (à jour / en retard / critique)
    class-fwe-notifier.php   → envois multicanaux + vérification des retards
    class-fwe-accounts.php   → lien utilisateur ↔ membre, invitation, redirections
    class-fwe-assets.php     → CSS/JS/polices/images
  admin/class-fwe-admin.php  → menus + gestionnaires admin-post (fwe_*)
  admin/views/*.php          → gabarits du back-office
  public/class-fwe-public.php→ shortcodes, connexion, inscription, contributions, profil
  public/views/*.php         → gabarits de l'espace membre
  assets/css/fwe.css         → design system (scopé .fwe-app / .fwe-admin)
  assets/js/fwe.js           → confirmations, onglets, copie, médiathèque (sans dépendance)
tests/test-logic.php         → tests métier (wp eval-file)
tests/seed-demo.php          → données de démonstration
bin/build-zip.sh             → construit dist/famille-we.zip
```

Tables : `members`, `cotisations` (UNIQUE `member_id, period`), `events`,
`support_cases`, `contributions`, `notifications` — préfixe `wp_fwe_`. Schéma dans
`FWE_Install::create_tables()` ; en cas de changement, **incrémenter `FWE_DB_VERSION`**
(la mise à jour passe par `maybe_upgrade()`).

## Conventions (à respecter)

- **Français d'abord** : chaînes sources en français, domaine `famille-we`, toujours via
  `__()`, `esc_html__()`, `_n()`… (une traduction anglaise pourra venir d'un `.po`).
  Attention au pluriel : `_n()` suit la règle anglaise (0 → pluriel) tant qu'aucune
  traduction n'est chargée ; prévoir un message dédié pour 0.
- **Sécurité** : chaque action passe par `admin-post.php` avec un nonce ; côté bureau
  `FWE_Admin::guard()` (capacité `fwe_manage` + nonce), côté membre `check_nonce()` +
  vérification que la ressource lui appartient. Entrées : `fwe_post_text()`,
  `sanitize_email()`, `fwe_choice()` (listes blanches), `fwe_parse_amount()`. Sorties :
  `esc_html`, `esc_attr`, `esc_url`. SQL : uniquement dans `FWE_Repo`, via `$wpdb->prepare()`.
- **Compatibilité SQL** : requêtes simples, sans fonctions propres à MySQL
  (pas de `DATE_FORMAT`, `CONCAT`, `ON DUPLICATE KEY`) — le plugin doit tourner sur MySQL
  comme sur SQLite. Les dates passent par PHP (`current_time()`, `wp_date()`).
- **Périodes** : chaînes `AAAA-MM` comparables directement ; utiliser
  `fwe_period_add()` / `fwe_periods_between()`, jamais d'arithmétique de dates à la main.
- **Gabarits** : logique dans les classes, vues en PHP simple ; variables de vue
  préfixées `$fwe_` ; `data-testid` sur tout élément interactif ou chiffre clé.
- **Sans JavaScript, tout doit marcher** (formulaires POST classiques) ; `fwe.js` améliore.

## Design system « Organic & Earthy »

Chaleur de la diaspora africaine + clarté d'un outil financier. Pas de bleu SaaS,
pas de glassmorphism : surfaces mates, tons terreux, espaces généreux.

| Jeton | Valeur |
|---|---|
| Fond / surface | `#F9F7F3` / `#FFFFFF`, bordure `#E8E3DA` |
| Primaire (terracotta) | `#C25934`, survol `#A64A29` |
| Secondaire (vert forêt) | `#2B4C3E`, survol `#1F382E` |
| Texte | `#1A201C` / `#5C6B62` / `#8A9A90` |
| Succès / alerte / danger | `#E9F2EC`+`#2D6643` · `#FDF3E1`+`#A86D00` · `#F9EAE9`+`#B9372E` |

- Titres **Cabinet Grotesk** (Fontshare), texte **Work Sans** (Google Fonts) ; libellés en
  petites capitales espacées (`.fwe-label`). Icônes **Lucide** inline via `fwe_icon()`.
- Composants : `.fwe-card`, `.fwe-btn--primary|secondary|outline|ghost|danger`,
  `.fwe-badge--success|warning|danger`, `.fwe-table`, `.fwe-stat`, `.fwe-progress`.
- Animation d'entrée discrète (`.fwe-rise`, y 10px → 0) et respect de `prefers-reduced-motion`.
- Images d'ambiance : `FWE_Assets::image()` (famille → connexion, rassemblement →
  événements, skyline de Dallas → bannière), surchargeables par le filtre `fwe_image_url`.
- L'espace membre utilise `alignwide` et des **container queries** : il s'adapte à la
  largeur que le thème lui laisse. Tout le CSS reste scopé (`.fwe-app`, `.fwe-admin`).

## Tester

Un WordPress de test suffit (jamais le site de production : les scripts vident les tables).

```bash
wp plugin activate famille-we
wp eval-file famille-we/tests/test-logic.php   # 62 assertions, code de sortie ≠ 0 si échec
wp eval-file famille-we/tests/seed-demo.php    # données réalistes + compte awa.kone / famille2026
```

Sans MySQL, WordPress tourne avec PHP 8 + l'extension *SQLite Database Integration*
(drop-in `wp-content/db.php`) et `php -S localhost:8890`. Avant de livrer : `php -l`
sur les fichiers, tests métier verts, parcours bureau + membre dans un navigateur
(connexion, paiement, retard, événement, soutien, annonce, inscription), aucune
erreur dans `wp-content/debug.log`, puis `bash famille-we/bin/build-zip.sh`.

## Pistes ouvertes

- Traduction anglaise (`languages/famille-we-en_US.po`).
- Export CSV des cotisations pour la trésorerie.
- Rappel préventif avant l'échéance du mois.
