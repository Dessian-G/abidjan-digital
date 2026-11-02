# Famille WÊ — plugin WordPress

Espace en ligne de l'association **Famille WÊ** (Dallas, Texas & Oklahoma) :
membres, cotisations mensuelles en dollars, retards avec alerte automatique,
événements et AG, soutien solidaire (naissance, mariage, décès) et notifications.

Le plugin s'installe sur le site WordPress existant de l'association.

## Installation

1. Récupérer le fichier **`dist/famille-we.zip`**.
2. Dans WordPress : **Extensions → Ajouter → Téléverser une extension**, choisir le zip, puis **Activer**.
3. À l'activation, le plugin crée :
   - le menu **Famille WÊ** dans l'administration ;
   - la page **Espace Famille WÊ** (shortcode `[famille_we_espace]`) : c'est l'adresse à partager aux membres ;
   - les rôles **Membre Famille WÊ** et **Bureau Famille WÊ** ;
   - une vérification quotidienne des retards.
4. Ouvrir **Famille WÊ → Réglages** et renseigner au minimum le **bénéficiaire et l'adresse Zelle** du trésorier.

Aucun « faux retard » à l'installation : le suivi des cotisations commence au mois
d'activation (modifiable dans *Réglages → Début du suivi des cotisations*).

## Utilisation

**Bureau** (administrateurs et rôle « Bureau Famille WÊ ») — menu *Famille WÊ* :

| Page | Rôle |
|---|---|
| Tableau de bord | Membres actifs, cotisations du mois, retards, événements, soutien, adhésions à valider, graphique sur 12 mois |
| Membres | Liste (recherche, Texas/Oklahoma, statut), fiche membre, invitation par e-mail |
| Cotisations | Saisie d'un paiement Zelle couvrant un ou plusieurs mois ; grille annuelle cliquable |
| Retards | Membres en retard, rappel manuel, vérification immédiate |
| Événements | Festivals, AG, rencontres ; prévenir les membres |
| Soutien | Cas naissance / mariage / décès, objectif, contributions à confirmer |
| Notifications | Annonce à tous (ou Texas / Oklahoma) et journal de chaque envoi |
| Réglages | Montant (20 $), seuil d'alerte (3 mois), Zelle, e-mail, SMS/WhatsApp |

**Membres** — page *Espace Famille WÊ* : connexion / inscription, situation de
cotisation, historique, coordonnées Zelle à copier, événements, contribution aux
cas de soutien, notifications, profil.

- Une inscription en ligne est **validée par le bureau**, sauf si le bureau avait
  déjà enregistré ce membre avec la même adresse e-mail (compte actif immédiatement).
- Autre shortcode : `[famille_we_evenements limit="6"]` affiche les prochains
  événements sur n'importe quelle page publique.

## Règles de cotisation

- 20 $ par mois et par membre actif, dus à partir du mois d'adhésion (ou du début du suivi).
- Le mois en cours est « à payer » ; un mois passé non payé est « en retard ».
- **Au-delà de 3 mois de retard** (4 mois ou plus), le membre reçoit automatiquement
  une alerte : dans son espace, par e-mail et — si Twilio est configuré — par SMS et
  WhatsApp. Une seule alerte par mois et par membre. Le seuil est réglable.

## SMS et WhatsApp

Ils passent par **Twilio** (service payant). Renseigner dans *Réglages* : Account SID,
Auth Token, numéro SMS et/ou numéro WhatsApp Twilio, puis cocher « Activer ».
Sans configuration, ces envois apparaissent « Non configuré » dans le journal.

Les e-mails utilisent l'envoi standard de WordPress : si l'hébergeur envoie mal
les e-mails, installer une extension SMTP (ex. WP Mail SMTP).

## Données et désinstallation

Les données sont dans des tables `wp_fwe_*`. Désactiver ou supprimer le plugin
**conserve** les données, sauf si *Réglages → Supprimer toutes les données* est coché.

## Développement

Voir [`CLAUDE.md`](CLAUDE.md). En bref :

```bash
bash famille-we/bin/build-zip.sh          # reconstruit dist/famille-we.zip
wp eval-file famille-we/tests/test-logic.php   # tests (site de test uniquement : vide les tables)
wp eval-file famille-we/tests/seed-demo.php    # données de démonstration (site de test)
```
